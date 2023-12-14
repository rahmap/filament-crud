<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
	    return $form
		    ->schema([
			    Section::make()
				    ->schema([
					    Select::make('category_id')
						    ->relationship('category', 'name')
					        ->native(false),
					    TextInput::make('title')->required(),
					    Textarea::make('content')->required(),
					    FileUpload::make('image')
						    ->directory('blog/images')
						    ->getUploadedFileNameForStorageUsing(
						    fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
							    ->prepend( getenv('APP_NAME') . '-' . substr(Str::uuid(), 0, 4) . '-'),
					        )
						    ->preserveFilenames()
						    ->required()
				    ])
		    ]);
    }

    public static function table(Table $table): Table
    {
	    return $table
		    ->columns([
			    TextColumn::make('id'),
			    TextInputColumn::make('title')->label('Judul'),
			    TextColumn::make('content'),
			    SelectColumn::make('category_id')
				    ->label('Category')
				    ->options(Category::pluck('name','id')->toArray()),
			    ImageColumn::make('image')->circular()
		    ])
		    ->filters([
			    SelectFilter::make('category_id')
				    ->label('Category')
				    ->options(Category::pluck('name','id')->toArray())
			        ->multiple()
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }    
}
