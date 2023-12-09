<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
	            Section::make()
		            ->schema([
			            TextInput::make('name')->required(),
			            FileUpload::make('photo')
				            ->directory('teacher/images')
				            ->getUploadedFileNameForStorageUsing(
					            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
						            ->prepend( getenv('APP_NAME') . '-' . substr(Str::uuid(), 0, 4) . '-'),
				            )
				            ->required(),
			            Select::make('courses')
				            ->relationship('courses', 'name')
				            ->multiple()
				            ->native(false)
			                ->preload(),
			            
		            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
	            TextColumn::make('id'),
	            TextColumn::make('name'),
	            ImageColumn::make('photo')->circular(),
	            TextColumn::make('Mata Pelajaran')
		            ->getStateUsing(fn (Model $record) => ($record->courses->pluck('name')))
		            ->label('Mata Pelajaran')
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }    
}
