<?php

declare(strict_types=1);

use NhanChauKP\ZaloBotSdk\Commands\HelpCommand;

return [
    /*
    |--------------------------------------------------------------------------
    | Your Zalo Bots
    |--------------------------------------------------------------------------
    | You may use multiple bots at once using the manager class. Each bot
    | that you own should be configured here.
    |
    | Here are each of the zalo bots config parameters.
    |
    | Supported Params:
    |
    | - name: The *personal* name you would like to refer to your bot as.
    |
    | - token: Your Zalo Bot's Access Token.
    |          Refer for more details: https://developers.zalo.me/
    |          Example: (string) 'your-zalo-bot-token'.
    |
    | - commands: (Optional) Commands to register for this bot,
    |             Supported Values: "Command Group Name", "Shared Command Name", "Full Path to Class".
    |             Default: Registers Global Commands.
    |             Example: (array) [
    |                 'admin', // Command Group Name.
    |                 'status', // Shared Command Name.
    |                 App\ZaloBots\Commands\HelloCommand::class,
    |                 App\ZaloBots\Commands\ByeCommand::class,
    |             ]
    */
    'bots' => [
        'default' => [
            'token' => env('ZALO_BOT_TOKEN', 'YOUR-ZALO-BOT-TOKEN'),
            'webhook_url' => env('ZALO_WEBHOOK_URL', 'YOUR-ZALO-BOT-WEBHOOK-URL'),
            'commands' => [
                // App\ZaloBots\Commands\MyCommand::class
            ],
        ],

        // 'mySecondBot' => [
        //     'token' => env('ZALO_BOT_TOKEN_2', 'YOUR-SECOND-BOT-TOKEN'),
        //     'webhook_url' => env('ZALO_WEBHOOK_URL_2', 'YOUR-SECOND-BOT-WEBHOOK-URL'),
        //     'commands' => [
        //         // App\ZaloBots\Commands\SecondBotCommand::class
        //     ],
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Bot Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the bots you wish to use as
    | your default bot for regular use.
    |
    */
    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Asynchronous Requests [Optional]
    |--------------------------------------------------------------------------
    |
    | When set to True, All the requests would be made non-blocking (Async).
    |
    | Default: false
    | Possible Values: (Boolean) "true" OR "false"
    |
    */
    'async_requests' => env('ZALO_ASYNC_REQUESTS', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Handler [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use a custom HTTP Client Handler.
    | Should be an instance of \NhanChauKP\ZaloBotSdk\Http\HttpClientInterface
    |
    | Default: GuzzlePHP
    |
    */
    'http_client_handler' => null,

    /*
    |--------------------------------------------------------------------------
    | Base Bot Url [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use a custom Base Bot Url.
    | Should be a local bot api endpoint or a proxy to the zalo api endpoint
    |
    | Default: https://bot-api.zapps.me/bot
    |
    */
    'base_bot_url' => env('ZALO_BASE_BOT_URL', 'https://bot-api.zapps.me/bot'),

    /*
    |--------------------------------------------------------------------------
    | Resolve Injected Dependencies in commands [Optional]
    |--------------------------------------------------------------------------
    |
    | Using Laravel's IoC container, we can easily type hint dependencies in
    | our command's constructor and have them automatically resolved for us.
    |
    | Default: true
    | Possible Values: (Boolean) "true" OR "false"
    |
    */
    'resolve_command_dependencies' => true,

    /*
    |--------------------------------------------------------------------------
    | Register Zalo Global Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use the SDK's built in command handler system,
    | You can register all the global commands here.
    |
    | Global commands will apply to all the bots in system and are always active.
    |
    | The command class should extend the \NhanChauKP\ZaloBotSdk\Commands\Command class.
    |
    | Default: The SDK registers, a help command which when a user sends /help
    | will respond with a list of available commands and description.
    |
    */
    'commands' => [
        HelpCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Groups [Optional]
    |--------------------------------------------------------------------------
    |
    | You can organize a set of commands into groups which can later,
    | be re-used across all your bots.
    |
    | You can create 4 types of groups:
    | 1. Group using full path to command classes.
    | 2. Group using shared commands: Provide the key name of the shared command
    | and the system will automatically resolve to the appropriate command.
    | 3. Group using other groups of commands: You can create a group which uses other
    | groups of commands to bundle them into one group.
    | 4. You can create a group with a combination of 1, 2 and 3 all together in one group.
    |
    | Examples shown below are by the group type for you to understand each of them.
    */
    'command_groups' => [
        /* // Group Type: 1
           'common' => [
                App\ZaloBots\Commands\TodoCommand::class,
                App\ZaloBots\Commands\TaskCommand::class,
           ],
        */

        /* // Group Type: 2
           'subscription' => [
                'start', // Shared Command Name.
                'stop', // Shared Command Name.
           ],
        */

        /* // Group Type: 3
            'auth' => [
                App\ZaloBots\Commands\LoginCommand::class,
                App\ZaloBots\Commands\SomeCommand::class,
            ],

            'stats' => [
                App\ZaloBots\Commands\UserStatsCommand::class,
                App\ZaloBots\Commands\SubscriberStatsCommand::class,
                App\ZaloBots\Commands\ReportsCommand::class,
            ],

            'admin' => [
                'auth', // Command Group Name.
                'stats' // Command Group Name.
            ],
        */

        /* // Group Type: 4
           'myBot' => [
                'admin', // Command Group Name.
                'subscription', // Command Group Name.
                'status', // Shared Command Name.
                'App\ZaloBots\Commands\BotCommand' // Full Path to Command Class.
           ],
        */
    ],

    /*
    |--------------------------------------------------------------------------
    | Shared Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | Shared commands let you register commands that can be shared between,
    | one or more bots across the project.
    |
    | This will help you prevent from having to register same command,
    | for each bot over and over again and make it easier to maintain them.
    |
    | Shared commands are not active by default, You need to use the key name to register them,
    | individually in each bot, with the 'commands' param or in 'command_groups' param
    | under the command group.
    |
    | Think of this as a central storage, to register, reusable commands.
    |
    | Command Handler: Either an instance of the command or a Closure.
    |
    */
    'shared_commands' => [
        // 'start' => App\ZaloBots\Commands\StartCommand::class,
        // 'stop' => App\ZaloBots\Commands\StopCommand::class,
        // 'status' => App\ZaloBots\Commands\StatusCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands Location [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like the commands to be auto-discovered,
    | You can provide the location(s) to look for the command files.
    |
    | Example: base_path('app/ZaloBots/Commands')
    |
    */
    'commands_paths' => [
        // base_path('app/ZaloBots/Commands'),
    ],
];
