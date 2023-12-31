<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    // 圖示,若群組有icon就子項不可設定,若子項有icon群組不可設定
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // 群組名稱
    protected static ?string $navigationGroup = '內容管理';

    // 名稱
    protected static ?string $modelLabel = '廣告';

    // 複數名
    protected static ?string $pluralModelLabel = '廣告';

    // 主要顯示欄位
    protected static ?string $recordTitleAttribute = 'name';

    // 目錄順位
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('link')
                    ->label('連結')
                    ->required()
                    ->url()
                    ->maxLength(255),

                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('封面')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->disk('medialibrary'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order_column') // 排序欄位
            ->paginatedWhileReordering() // 排序使用分頁
            ->reorderRecordsTriggerAction(
                fn (Action $action, bool $isReordering) => $action
                    ->button()
                    ->label($isReordering ? 'Disable reordering' : 'Enable reordering'),
            ) // 排序開關
            ->defaultSort('order_column', 'desc') // 需要排序功能還是可以使用資料顯示順序
            ->paginated([10, 25, 50])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),

                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('封面')
                    ->collection('cover'),

                Tables\Columns\TextColumn::make('name')
                    ->label('名稱')
                    ->searchable(),

                // 排序顯示數字
                // Tables\Columns\TextColumn::make('order_column')
                //     ->label('排序'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // 自己做的排序
                // Action::make('up')
                //     ->label('Up')
                //     ->action(fn (Model $record) => $record->moveOrderUp())
                //     ->icon('heroicon-m-arrow-up'),

                // 自己做的排序
                // Action::make('down')
                //     ->label('Down')
                //     ->action(fn (Model $record) => $record->moveOrderDown())
                //     ->icon('heroicon-m-arrow-down'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
