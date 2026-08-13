<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\QuoteRequest;
use App\Services\TelegramNotifier;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Throwable;

class TelegramCheck extends Command
{
    protected $signature = 'telegram:check
        {--message= : Send this plain text instead of a sample lead}
        {--lead= : Re-send an existing quote request by id}
        {--latest : Re-send the most recent quote request}
        {--dry : Verify the token, chat and bot membership without sending anything}';

    protected $description = 'Verify the Telegram bot integration and send a test message to the sales group';

    public function handle(TelegramNotifier $telegram): int
    {
        $this->components->info('Telegram integration check');

        $this->showConfig($telegram);

        if (! $telegram->hasToken()) {
            $this->components->error('TELEGRAM_BOT_TOKEN is not set. Add it to .env, then run: php artisan config:clear');

            return self::FAILURE;
        }

        if (! $telegram->isConfigured()) {
            $this->components->error('TELEGRAM_CHAT_ID is not set. Add it to .env, then run: php artisan config:clear');

            return self::FAILURE;
        }

        if (! $this->checkBot($telegram)) {
            return self::FAILURE;
        }

        if (! $this->checkChat($telegram)) {
            return self::FAILURE;
        }

        if ($this->option('dry')) {
            $this->components->info('Dry run: token and chat verified, nothing sent.');

            return self::SUCCESS;
        }

        return $this->sendTestMessage($telegram);
    }

    private function showConfig(TelegramNotifier $telegram): void
    {
        $this->components->twoColumnDetail('Bot token', $telegram->maskedToken());
        $this->components->twoColumnDetail('Chat id', $telegram->chatId() ?? '—');
        $this->components->twoColumnDetail('Thread id', $telegram->threadId() ?? '— (no topic)');
        $this->newLine();
    }

    private function checkBot(TelegramNotifier $telegram): bool
    {
        $response = $this->callApi($telegram, 'getMe');

        if ($response === null) {
            return false;
        }

        if ($response->failed()) {
            $this->reportApiError('Could not authenticate the bot', $response);

            if ($response->status() === 401) {
                $this->components->warn('The token is rejected by Telegram. Re-copy it from @BotFather.');
            }

            return false;
        }

        $bot = $response->json('result', []);
        $this->components->twoColumnDetail(
            '<fg=green>✓</> Bot',
            '@'.($bot['username'] ?? '?').' ('.($bot['first_name'] ?? '?').')'
        );

        return true;
    }

    private function checkChat(TelegramNotifier $telegram): bool
    {
        $response = $this->callApi($telegram, 'getChat', ['chat_id' => $telegram->chatId()]);

        if ($response === null) {
            return false;
        }

        if ($response->failed()) {
            $this->reportApiError('Could not read the target chat', $response);
            $this->hintChatFailure($response);

            return false;
        }

        $chat = $response->json('result', []);
        $title = $chat['title'] ?? $chat['username'] ?? $chat['first_name'] ?? '?';

        $this->components->twoColumnDetail(
            '<fg=green>✓</> Chat',
            $title.' ('.($chat['type'] ?? '?').')'
        );

        if (($chat['type'] ?? null) === 'private') {
            $this->components->warn('This chat id points at a private chat, not a group. Group ids are negative, e.g. -1001234567890.');
        }

        return true;
    }

    private function sendTestMessage(TelegramNotifier $telegram): int
    {
        $text = $this->resolveMessage($telegram);

        $response = $this->callApi($telegram, 'sendMessage', $telegram->messagePayload($text));

        if ($response === null) {
            return self::FAILURE;
        }

        if ($response->failed()) {
            $this->reportApiError('Could not send the message', $response);
            $this->hintChatFailure($response);

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->info('Message delivered to the group. Check Telegram to confirm the formatting.');

        return self::SUCCESS;
    }

    /**
     * Decide what to send: a custom string, a real lead, or a sample lead.
     */
    private function resolveMessage(TelegramNotifier $telegram): string
    {
        if (filled($this->option('message'))) {
            return e((string) $this->option('message'));
        }

        $quoteRequest = $this->resolveQuoteRequest();

        return $telegram->previewQuoteRequest($quoteRequest);
    }

    private function resolveQuoteRequest(): QuoteRequest
    {
        if ($this->option('latest')) {
            $latest = QuoteRequest::latest('id')->first();

            if ($latest === null) {
                $this->components->warn('No quote requests in the database yet, sending a sample instead.');

                return $this->sampleQuoteRequest();
            }

            return $latest;
        }

        if (filled($this->option('lead'))) {
            $lead = QuoteRequest::find($this->option('lead'));

            if ($lead === null) {
                $this->components->warn('Quote request #'.$this->option('lead').' not found, sending a sample instead.');

                return $this->sampleQuoteRequest();
            }

            return $lead;
        }

        return $this->sampleQuoteRequest();
    }

    /**
     * An unsaved record, so the check never writes a fake lead to the database.
     */
    private function sampleQuoteRequest(): QuoteRequest
    {
        return new QuoteRequest([
            'name' => 'Тестовая заявка',
            'company' => 'Проверка интеграции',
            'phone' => '+998 90 123-45-67',
            'email' => 'test@example.com',
            'product_interest' => 'Футболки',
            'quantity' => '500 шт',
            'message' => 'Это тестовое сообщение от команды telegram:check. Заявка не сохранена в базе.',
        ]);
    }

    private function callApi(TelegramNotifier $telegram, string $method, array $payload = []): ?Response
    {
        try {
            return $telegram->call($method, $payload);
        } catch (Throwable $e) {
            $this->components->error('Could not reach api.telegram.org: '.$e->getMessage());
            $this->components->warn('Check outbound network access and whether Telegram is blocked from this host.');

            return null;
        }
    }

    private function reportApiError(string $context, Response $response): void
    {
        $this->components->error($context.' (HTTP '.$response->status().')');

        $description = $response->json('description');

        if (filled($description)) {
            $this->components->twoColumnDetail('Telegram says', (string) $description);
        }
    }

    private function hintChatFailure(Response $response): void
    {
        $description = strtolower((string) $response->json('description'));

        match (true) {
            str_contains($description, 'chat not found') => $this->components->warn(
                'Wrong chat id, or the bot was never added to the group. Group ids are negative — keep the leading minus sign.'
            ),
            str_contains($description, 'kicked') || str_contains($description, 'not a member') => $this->components->warn(
                'The bot is not in the group. Add it as a member (administrator is safest for supergroups).'
            ),
            str_contains($description, 'not enough rights') => $this->components->warn(
                'The bot is in the group but cannot post. Promote it to administrator, or allow members to send messages.'
            ),
            str_contains($description, 'thread not found') => $this->components->warn(
                'TELEGRAM_THREAD_ID does not match a topic in this group. Clear it, or copy the id from the topic link.'
            ),
            default => null,
        };
    }
}
