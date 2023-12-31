<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    // 圖示,若群組有icon就子項不可設定,若子項有icon群組不可設定
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // 群組名稱
    protected static ?string $navigationGroup = '內容管理';

    // 名稱
    protected static ?string $modelLabel = '貼文';

    // 複數名
    protected static ?string $pluralModelLabel = '貼文';

    // 主要顯示欄位
    protected static ?string $recordTitleAttribute = 'title';

    // 目錄順位
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('標題')
                    ->required()
                    ->maxLength(255),

                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('封面')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->disk('medialibrary'),

                SpatieTagsInput::make('tags')
                    ->label('標籤')
                    ->type('categories'),

                RichEditor::make('content')
                    ->label('內文')
                    ->fileAttachmentsDisk('article')
                    // ->fileAttachmentsDirectory('article') // filesystems.php有設prefix就不需要再設
                    ->fileAttachmentsVisibility('public')
                    ->columnSpan(2),

                Forms\Components\Select::make('user_id')
                    ->label('用戶')
                    ->searchable()
                    // 關聯用戶使用relationship和getOptionLabelUsing
                    // ->relationship('user', 'name')
                    // 或使用getSearchResultsUsing(自訂條件)和getOptionLabelUsing(自訂顯示)
                    ->getSearchResultsUsing(
                        fn (string $search) => User::select('id', DB::raw("CONCAT_WS(', ',id,name,email) as name"))
                            ->orWhere('id', $search)
                            ->orWhere('name', 'like', "%{$search}%")
                            // ->orWhere('email', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('name', 'id')
                    )
                    // 使用getOptionLabelUsing性能降低
                    ->getOptionLabelUsing(fn ($value): ?string => implode(', ', User::find($value)->only('id', 'name', 'email')))
                    // 或使用$table->string('full_name')->virtualAs('concat(first_name, \' \', last_name)');建立一個欄位顯示
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->paginated([10, 25, 50])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('封面')
                    ->collection('cover'),

                Tables\Columns\TextColumn::make('title')
                    ->label('標題')
                    ->searchable(),

                SpatieTagsColumn::make('tags')
                    ->label('標籤')
                    ->type('categories'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
