# Zalo Bot SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nhanchaukp/zalo-bot-sdk.svg?style=flat-square)](https://packagist.org/packages/nhanchaukp/zalo-bot-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/nhanchaukp/zalo-bot-sdk.svg?style=flat-square)](https://packagist.org/packages/nhanchaukp/zalo-bot-sdk)

Một SDK mạnh mẽ và dễ sử dụng để xây dựng Zalo Bot trên Laravel. Package này được thiết kế với cấu trúc tương tự như Telegram Bot SDK, giúp việc phát triển và bảo trì trở nên đơn giản.

## Tính năng

- ✅ Hỗ trợ nhiều bot cùng lúc
- ✅ Hệ thống command mạnh mẽ và mở rộng
- ✅ Cấu hình linh hoạt và dễ dàng
- ✅ HTTP Client tùy chỉnh (mặc định sử dụng GuzzlePHP)
- ✅ Laravel Facade hỗ trợ
- ✅ Webhook handling
- ✅ Command groups và shared commands
- ✅ Auto-discovery commands
- ✅ Exception handling

## Cài đặt

Bạn có thể cài đặt package qua Composer:

```bash
composer require nhanchaukp/zalo-bot-sdk
```

## Publish Config

Publish file config để tùy chỉnh:

```bash
php artisan vendor:publish --tag="zalo-bot-config"
```

Hoặc:

```bash
php artisan vendor:publish --provider="NhanChauKP\ZaloBotSdk\ZaloBotServiceProvider" --tag="config"
```

## Cấu hình

### Biến môi trường

Thêm các biến sau vào file `.env`:

```env
ZALO_BOT_TOKEN=your-zalo-bot-token
ZALO_WEBHOOK_URL=https://yourapp.com/webhook/zalo
ZALO_BASE_BOT_URL=https://bot-api.zapps.me/bot
ZALO_ASYNC_REQUESTS=false
```

### Cấu hình nhiều bot

Trong file `config/zalo-bot.php`:

```php
'bots' => [
    'default' => [
        'token' => env('ZALO_BOT_TOKEN'),
        'webhook_url' => env('ZALO_WEBHOOK_URL'),
        'commands' => [
            // App\ZaloBots\Commands\StartCommand::class
        ],
    ],
    
    'support_bot' => [
        'token' => env('ZALO_SUPPORT_BOT_TOKEN'),
        'webhook_url' => env('ZALO_SUPPORT_WEBHOOK_URL'),
        'commands' => [
            // App\ZaloBots\Commands\SupportCommand::class
        ],
    ],
],

'default' => 'default', // Bot mặc định
```

## Sử dụng cơ bản

### Gửi tin nhắn

```php
use NhanChauKP\ZaloBotSdk\Facades\ZaloBot;

// Sử dụng bot mặc định
ZaloBot::sendMessage('chat_id', 'Xin chào!');

// Sử dụng bot cụ thể
ZaloBot::bot('support_bot')->sendMessage('chat_id', 'Chúng tôi có thể giúp gì?');
```

### Gửi hình ảnh

```php
ZaloBot::sendPhoto('chat_id', 'https://example.com/image.jpg', 'Caption cho hình ảnh');
```

### Gửi sticker

```php
// Lấy sticker từ https://stickers.zaloapp.com/
ZaloBot::sendSticker('chat_id', 'sticker_id_from_stickers_zaloapp_com');
```

### Hiển thị trạng thái (sendChatAction)

Tham khảo tài liệu: https://bot.zapps.me/docs/apis/sendChatAction/

```php
use NhanChauKP\ZaloBotSdk\Enums\ChatAction;

// Đang soạn tin nhắn
ZaloBot::sendChatAction('chat_id', ChatAction::Typing);

// Đang tải ảnh (sắp ra mắt)
ZaloBot::sendChatAction('chat_id', ChatAction::UploadPhoto);
```

### Webhook

```php
// Set webhook
ZaloBot::setWebhook('https://yourapp.com/webhook/zalo');

// Xóa webhook
ZaloBot::deleteWebhook();

// Lấy thông tin webhook
$webhookInfo = ZaloBot::getWebhookInfo();
```

## Tạo Commands

### Tạo Command class

```php
<?php

namespace App\ZaloBots\Commands;

use NhanChauKP\ZaloBotSdk\Commands\Command;
use NhanChauKP\ZaloBotSdk\ZaloBot;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Bắt đầu cuộc trò chuyện';
    protected array $aliases = ['begin'];

    public function handle(array $update, ZaloBot $bot): mixed
    {
        return $this->reply($update, $bot, 'Xin chào! Chào mừng bạn đến với bot của chúng tôi.');
    }
}
```

### Đăng ký Commands

Trong config file:

```php
'commands' => [
    App\ZaloBots\Commands\StartCommand::class,
    App\ZaloBots\Commands\HelpCommand::class,
],
```

Hoặc đăng ký động:

```php
ZaloBot::addCommand(App\ZaloBots\Commands\StartCommand::class);
ZaloBot::addCommands([
    App\ZaloBots\Commands\StartCommand::class,
    App\ZaloBots\Commands\HelpCommand::class,
]);
```

### Command Groups

```php
'command_groups' => [
    'admin' => [
        App\ZaloBots\Commands\AdminStartCommand::class,
        App\ZaloBots\Commands\AdminStatsCommand::class,
    ],
    
    'user' => [
        App\ZaloBots\Commands\UserProfileCommand::class,
        App\ZaloBots\Commands\UserSettingsCommand::class,
    ],
],

// Sử dụng groups trong bot
'bots' => [
    'admin_bot' => [
        'token' => env('ZALO_ADMIN_BOT_TOKEN'),
        'commands' => [
            'admin', // Sử dụng command group
        ],
    ],
],
```

### Shared Commands

```php
'shared_commands' => [
    'help' => App\ZaloBots\Commands\HelpCommand::class,
    'start' => App\ZaloBots\Commands\StartCommand::class,
],

// Sử dụng trong bot
'bots' => [
    'default' => [
        'commands' => [
            'help', // Shared command
            'start', // Shared command
        ],
    ],
],
```

## Xử lý Webhook

### Tạo Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NhanChauKP\ZaloBotSdk\Facades\ZaloBot;

class ZaloWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();
        
        try {
            $result = ZaloBot::processUpdate($update);
            
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('Zalo webhook error: ' . $e->getMessage());
            
            return response()->json(['status' => 'error'], 500);
        }
    }
}
```

### Route

```php
// routes/web.php hoặc routes/api.php
Route::post('/webhook/zalo', [ZaloWebhookController::class, 'handle']);
```

## Command nâng cao

### Command với tham số

```php
class WeatherCommand extends Command
{
    protected string $name = 'weather';
    protected string $description = 'Xem thời tiết của thành phố';
    protected array $arguments = [
        'city' => 'Tên thành phố (bắt buộc)',
    ];

    public function handle(array $update, ZaloBot $bot): mixed
    {
        $text = $this->getMessageText($update);
        $args = $this->getCommandArguments($text);
        
        if (empty($args)) {
            return $this->reply($update, $bot, 'Vui lòng nhập tên thành phố. Ví dụ: /weather Hà Nội');
        }
        
        $city = implode(' ', $args);
        
        // Logic lấy thời tiết
        $weather = $this->getWeather($city);
        
        return $this->reply($update, $bot, "Thời tiết tại {$city}: {$weather}");
    }
    
    private function getWeather(string $city): string
    {
        // Implementation logic
        return "Nhiều mây, 25°C";
    }
}
```

## Cấu hình nâng cao

### Custom HTTP Client

```php
// Trong config
'http_client_handler' => new CustomHttpClient(),

// Hoặc bind trong Service Provider
$this->app->bind('zalo-bot.http_client', function() {
    return new CustomHttpClient();
});
```

### Auto-discovery Commands

```php
'commands_paths' => [
    base_path('app/ZaloBots/Commands'),
    base_path('app/CustomCommands'),
],
```

## Testing

```bash
composer test
```

## Changelog

Vui lòng xem [CHANGELOG](CHANGELOG.md) để biết thêm thông tin về những thay đổi gần đây.

## Contributing

Vui lòng xem [CONTRIBUTING](CONTRIBUTING.md) để biết chi tiết.

## Security Vulnerabilities

Nếu bạn phát hiện lỗ hổng bảo mật, vui lòng gửi email đến nhanchauthai@gmail.com.

## Credits

- [NhanChauKP](https://github.com/nhanchaukp)
- [All Contributors](../../contributors)

## License

MIT License. Vui lòng xem [License File](LICENSE.md) để biết thêm thông tin.
