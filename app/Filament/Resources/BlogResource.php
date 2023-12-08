<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
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
			    TextColumn::make('title'),
			    TextColumn::make('content'),
			    ImageColumn::make('image')->circular()
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }    
}
