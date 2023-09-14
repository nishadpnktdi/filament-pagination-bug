<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\CustomerResource\RelationManagers\AssessmentsRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\ProductsRelationManager;
use App\Models\Assessment;
use App\Models\Customer;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('register_number')
                    ->required()
                    ->unique(Customer::class, 'register_number', ignoreRecord: true),
                Select::make('store_id')
                    ->label('Store')
                    ->options(Store::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('first_name')
                    ->autofocus()
                    ->required()
                    ->maxLength(45)
                    ->placeholder('First Name'),
                TextInput::make('last_name')
                    ->required()
                    ->maxLength(45)
                    ->placeholder('Last Name'),

                Fieldset::make('Contact details')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(Customer::class, 'email', ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('Email')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('user.email', $state)),
                        TextInput::make('phone')
                            ->required()
                            ->unique(Customer::class, 'phone', ignoreRecord: true)
                            ->maxLength(45)
                            ->placeholder('Phone'),
                        TextInput::make('alternate_phone')
                            ->nullable()
                            ->unique(Customer::class, 'alternate_phone', ignoreRecord: true)
                            ->maxLength(45)
                            ->placeholder('Alternate Phone'),
                        Textarea::make('address')
                            ->required()
                            ->placeholder('Address'),
                    ]),

                Fieldset::make('credentials')
                    ->label('Authentication Credentials')
                    ->relationship('user')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('Email'),
                        TextInput::make('password')
                            ->placeholder('Password')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])
                    ->mutateRelationshipDataBeforeCreateUsing(function($data, $get){
                        $data['name'] = $get('first_name') . ' ' . $get('last_name');

                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function($data, $get){
                        $data['name'] = $get('first_name') . ' ' . $get('last_name');

                        return $data;
                    }),

                Checkbox::make('is_active')
                    ->label('Customer Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex(),
                TextColumn::make('register_number')
                    ->searchable(),
                TextColumn::make('name')
                    ->getStateUsing(fn (Customer $record) => $record['first_name'] . ' ' . $record['last_name']),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('branch')
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Section::make([
                    TextEntry::make('register_number'),
                    TextEntry::make('store.name'),
                    TextEntry::make('first_name'),
                    TextEntry::make('last_name'),
                    TextEntry::make('email')
                        ->icon('heroicon-m-envelope'),
                    TextEntry::make('phone')
                        ->icon('heroicon-s-phone'),
                    TextEntry::make('alternate_phone')
                        ->icon('heroicon-s-phone'),
                    TextEntry::make('address')
                        ->icon('heroicon-s-home'),
                    TextEntry::make('created_at')
                        ->dateTime('d M Y, h:m A'),
                    IconEntry::make('is_active')
                        ->boolean(),
                ])->columns(2),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
