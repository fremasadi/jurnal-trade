<?php

namespace App\Filament\Resources\Trades\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // === USER otomatis dari login ===
                TextInput::make('user_id')
                    ->default(fn () => Auth::id())
                    ->disabled() // tidak bisa diubah
                    ->dehydrated(true) // tetap dikirim ke database
                    ->hidden(), // disembunyikan di form UI

                // === MENTOR (optional) ===
               Select::make('mentor_id')
                    ->label('Mentor (Optional)')
                    ->relationship(
                        name: 'mentor',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->where('user_id', Auth::id())
                    )
                    ->nullable(),

                Select::make('pair_id')
                    ->label('Pair')
                    ->relationship(
                        name: 'pair',
                        titleAttribute: 'symbol_name',
                        modifyQueryUsing: fn ($query) => $query->where('user_id', Auth::id())
                    )
                    ->required(),


                // === DIRECTION ===
                Select::make('direction')
                    ->label('Direction')
                    ->options([
                        'Buy' => 'Buy',
                        'Sell' => 'Sell',
                    ])
                    ->required(),

                // === PRICES ===
                TextInput::make('entry_price')
                    ->label('Entry Price')
                    ->required()
                    ->numeric(),
                TextInput::make('exit_price')
                    ->label('Exit Price')
                    ->numeric()
                    ->nullable(),
                TextInput::make('sl_price')
                    ->label('Stop Loss')
                    ->numeric()
                    ->nullable(),
                TextInput::make('tp_price')
                    ->label('Take Profit')
                    ->numeric()
                    ->nullable(),
                TextInput::make('lot_size')
                    ->label('Lot Size')
                    ->numeric()
                    ->nullable(),

                // === TIME ===
                DateTimePicker::make('entry_time')
                    ->label('Entry Time')
                    ->required(),
                DateTimePicker::make('exit_time')
                    ->label('Exit Time')
                    ->nullable(),

                // === RESULT ===
                Select::make('result')
                    ->label('Result')
                    ->options([
                        'Profit' => 'Profit',
                        'Loss' => 'Loss',
                    ])
                    ->nullable(),

                TextInput::make('pnl_value')
                    ->label('PnL Value')
                    ->numeric()
                    ->nullable(),

                // === TEXT AREAS ===
                Textarea::make('reason_entry')
                    ->label('Reason for Entry')
                    ->nullable()
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Notes')
                    ->nullable()
                    ->columnSpanFull(),

                // === SCREENSHOT UPLOAD ===
                FileUpload::make('screenshot_img')
                    ->label('Screenshot')
                    ->image()
                    ->directory('trades/screenshots')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
