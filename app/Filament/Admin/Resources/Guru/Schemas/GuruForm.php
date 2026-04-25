<?php

namespace App\Filament\Admin\Resources\Guru\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pribadi')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique('guru', 'email', ignorable: fn ($record) => $record),
                        TextInput::make('no_hp')
                            ->label('No. Handphone')
                            ->required(),
                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status')
                            ->options(['baru' => 'Baru', 'semi' => 'Standar', 'senior' => 'Senior'])
                            ->required(),
                    ])->columns(2),

                Section::make('Akun Login')
                    ->description('Akun akan digunakan guru untuk login ke sistem.')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(6)
                            ->maxLength(255),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->same('password')
                            ->maxLength(255),
                    ])->columns(2)
                    ->visibleOn('create'),
            ]);
    }
}
