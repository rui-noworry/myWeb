package dkt.student.database;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import dkt.student.MyContants;
import dkt.student.model.Note;
import dkt.student.model.NoteType;
import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

public class NoteServer {
	private File path = new File(MyContants.RESOURCE_HANDWRITING);
	private File f = new File(MyContants.RESOURCE_HANDWRITING_DEFAULT_NAME);
	private SQLiteDatabase database;
	private String tableName = "note";
	private String typeTableName = "notetype";
	private String columNoteId = "noteId";
	private String columNoteName = "noteName";
	private String columNoteType = "noteType";
	private String columNoteTypeId = "noteTypeId";
	private String columNoteCreateTime = "noteCreateTime";
	private String columNoteUpdateTime = "noteUpdateTime";
	private String columNotePath = "notePath";
	private String columNotePic = "notePic";

	public NoteServer() {
		createFile();
		createTableIfNotExists();

	}

	private void createFile() {
		if (!path.exists()) {
			path.mkdir();
		}
		if (!f.exists()) {
			try {
				f.createNewFile();
			} catch (IOException e) {
				e.printStackTrace();
			}

		}
	}

	private void createTableIfNotExists() {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();
		database.execSQL("CREATE TABLE IF NOT EXISTS " + tableName + " ("
				+ columNoteId + " integer primary key autoincrement, "
				+ columNoteType + " integer, " 
				+ columNoteCreateTime+ " bigint, "
				+ columNoteUpdateTime+ " bigint, "
				+ columNoteName + " varchar(30), "
				+ columNotePath + " varchar(100), " 
				+columNotePic+" integer );");
		String str_sql = "CREATE TABLE IF NOT EXISTS " + typeTableName + " ("
        		+ columNoteTypeId + " integer primary key autoincrement, "
        		+ columNoteType + " varchar(30), "				  
        		+ columNotePic+" integer "+ ");";
		database.execSQL(str_sql);
		Cursor cursor = database.rawQuery("SELECT * FROM "
			      + typeTableName, null);  
		int pageNumTotal = cursor.getCount();
		if(pageNumTotal == 0){
			NoteType notetype = new NoteType();
			notetype.setColumNoteTypeName("默认");
			notetype.setColumNoteTypeId(0);
			ContentValues values = new ContentValues();
			values.put(columNoteType, notetype.getColumNoteTypeName());
			values.put(columNoteTypeId, notetype.getColumNoteTypeId());
			values.put(columNotePic, notetype.getColumNotePic());
			database.insert(typeTableName, null, values);
		}
		
		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}

	public void save(Note note) {

		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(columNoteName, note.getNoteName());
		values.put(columNoteType, note.getNoteType());
		values.put(columNoteCreateTime, note.getNoteCreateTime());
		values.put(columNoteUpdateTime, note.getNoteCreateTime());
		values.put(columNotePath, note.getNotePath());
		values.put(columNotePic, note.getNotePic());
		database.insert(tableName, null, values);

		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	
	public void saveType(NoteType note){
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(columNoteType, note.getColumNoteTypeName());
		values.put(columNotePic, note.getColumNotePic());
		database.insert(typeTableName, null, values);

		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	public void update(Note note) {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(columNoteName, note.getNoteName());
		values.put(columNoteType, note.getNoteType());
		values.put(columNoteUpdateTime, note.getNoteCreateTime());
		database.update(tableName, values, columNoteId+" = ?",
				new String[] { String.valueOf(note.getNoteId()) });

		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	public void updateType(NoteType notetype){
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(columNoteType, notetype.getColumNoteTypeName());
		database.update(typeTableName, values, columNoteTypeId+" = ?",
				new String[] { String.valueOf(notetype.getColumNoteTypeId()) });

		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	
	public List<Note> getMyData(int typeId) {
		List<Note> list = new ArrayList<Note>();
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(tableName, new String[] { columNoteId,
				columNoteName, columNoteType, columNoteCreateTime, columNoteUpdateTime, columNotePath ,columNotePic}, "noteType="+typeId,
				null, null, null, null);
		while (cursor.moveToNext()) {
			Note note = new Note();
			note.setNoteId(cursor.getInt(0));
			note.setNoteName(cursor.getString(1));
			note.setNoteType(cursor.getInt(2));
			note.setNoteCreateTime(cursor.getLong(3));
			note.setNoteUpdateTime(cursor.getLong(4));
			note.setNotePath(cursor.getString(5));
            note.setNotePic(cursor.getInt(6));
			list.add(note);
		}
		database.close();
		return list;
	}
	public List<Note> getSearchData(String searchString) {
		List<Note> list = new ArrayList<Note>();
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(tableName, new String[] { columNoteId,
				columNoteName, columNoteType, columNoteCreateTime, columNoteUpdateTime, columNotePath ,columNotePic}, "noteName like ?",
				new String[]{"%"+searchString+"%"}, null, null, "noteCreateTime desc", null);
		while (cursor.moveToNext()) {
			Note note = new Note();
			note.setNoteId(cursor.getInt(0));
			note.setNoteName(cursor.getString(1));
			note.setNoteType(cursor.getInt(2));
			note.setNoteCreateTime(cursor.getLong(3));
			note.setNoteUpdateTime(cursor.getLong(4));
			note.setNotePath(cursor.getString(5));
            note.setNotePic(cursor.getInt(6));
			list.add(note);
		}
		database.close();
		return list;
	}
	public List<Note> getData() {
		List<Note> list = new ArrayList<Note>();
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(tableName, new String[] { columNoteId,
				columNoteName, columNoteType, columNoteCreateTime, columNoteUpdateTime, columNotePath ,columNotePic}, null,
				null, null, null, "noteCreateTime desc", null);
		while (cursor.moveToNext()) {
			Note note = new Note();
			note.setNoteId(cursor.getInt(0));
			note.setNoteName(cursor.getString(1));
			note.setNoteType(cursor.getInt(2));
			note.setNoteCreateTime(cursor.getLong(3));
			note.setNoteUpdateTime(cursor.getLong(4));
			note.setNotePath(cursor.getString(5));
            note.setNotePic(cursor.getInt(6));
			list.add(note);
		}
		database.close();
		return list;
	}
	public List<NoteType> getTypeData(){
		List<NoteType> list = new ArrayList<NoteType>();
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(typeTableName, new String[] { columNoteTypeId,
				columNoteType,columNotePic}, null,
				null, null, null, null);
		while (cursor.moveToNext()) {
			NoteType notetype = new NoteType();
			notetype.setColumNoteTypeName(cursor.getString(1));
			notetype.setColumNoteTypeId(cursor.getInt(0));
			notetype.setColumNotePic(cursor.getInt(2));
			list.add(notetype);
		}
		database.close();
		return list;
	}
	public void delete(int id) {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.delete(tableName, columNoteId+" = ?",
				new String[] { String.valueOf(id) });
		database.close();
	}
	public void deleteType(int id) {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.delete(typeTableName, columNoteTypeId+" = ?",
				new String[] { String.valueOf(id) });
		database.close();
	}
}
