<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    // 資料源
    protected static ?string $model = User::class;

    // 圖示
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // 群組
    protected static ?string $navigationGroup = 'Member';

    protected static ?int $navigationGroupSort = 1;

    // 名稱
    protected static ?string $modelLabel = 'User';

    // 複數名
    protected static ?string $pluralModelLabel = 'Users';

    // 主要顯示欄位
    protected static ?string $recordTitleAttribute = 'name';

    // 順位
    protected static ?int $navigationSort = 1;

    // 表單
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('信箱')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('密碼')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // 分頁
            ->paginated([10, 25, 50])
            // 欄位
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('名稱')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('信箱')
                    ->searchable(),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('驗證')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // 過濾
            ->filters([
                Tables\Filters\Filter::make('驗證')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            ])
            // 單獨按鈕
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),

                // wip
                // Tables\Actions\AssociateAction::make()
                //     ->recordSelectSearchColumns(['id', 'name']),
            ])
            // 批量按鈕
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // 可以直接放到bulkActions,但東西會變的很多
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                    // Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            // 無資料時
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // 取得定義的頁面
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}