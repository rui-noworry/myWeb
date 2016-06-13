package dkt.teacher.database;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import dkt.teacher.model.Homework;
import dkt.teacher.MyContants;

public class MyHomeWorkServer {

	private SQLiteDatabase mysql ; 
	private File main_path = new File(MyContants.MAIN_PATH);
	private File path = new File(MyContants.HOMEWORK_PATH);
	private File f;
	
	private String tableName = "homework";
	private String Id = "id"; // 
	private String toId = "toid";  // 题目id
	private String toAnswerBitmap = "toanswerbitmap"; // 原笔迹图片
	
	private String toTableName = "totable"; // 主观题批改图片表
		
	public MyHomeWorkServer(String pathString) {
		this.f = new File(MyContants.HOMEWORK_PATH + "/" + pathString);
		createFile();
		createTableIfNotExists();
	}
	
	/**
	 *  判断文件夹和文件是否存在，否则创建
	 * */
	private void createFile() {
		if (!main_path.exists()) {
			main_path.mkdir();
		}
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
	
	/**
	 *  如果该表不存在则创建
	 * */
	private void createTableIfNotExists() {
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();
		mysql.execSQL("CREATE TABLE IF NOT EXISTS " + tableName + " ("
				+ Id + " integer primary key autoincrement, "
				+ toId + " text, "
				+toAnswerBitmap+" BLOB );");
		mysql.execSQL("CREATE TABLE IF NOT EXISTS " + toTableName + " ("
				+ Id + " integer primary key autoincrement, "
				+ toId + " text, "
				+toAnswerBitmap+" BLOB );");
		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
		
	}
	
	public void clearFeedTable(){
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();
		String sql = "DELETE FROM " + tableName +";";
		String sql1 = "DELETE FROM " + toTableName +";";
		mysql.execSQL(sql);
		mysql.execSQL(sql1);
		revertSeq();
		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
	}

	private void revertSeq() {
		String sql = "update sqlite_sequence set seq=0 where name='"+ tableName +"'";
		mysql.execSQL(sql);
		String sql1 = "update sqlite_sequence set seq=0 where name='"+ toTableName +"'";
		mysql.execSQL(sql1);
	}
	
	/**
	 *  新增多个
	 * */
	public void insertHomeworkList(List<Homework> homework) {

		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();
		
		for(int i=0;i<homework.size();i++) {
			ContentValues values = new ContentValues();
			values.put(toId, homework.get(i).getToId());
			values.put(toAnswerBitmap, homework.get(i).getToAnswerBitmap());
			
			mysql.insert(tableName, null, values);
			mysql.insert(toTableName, null, values);
			System.out.println("新创建了第"+i);
		}
		Cursor cursor = mysql.rawQuery("SELECT * FROM "
			      + toTableName , null);
		while (cursor.moveToNext()) {
			System.out.println("新增的页码"+cursor.getString(cursor.getColumnIndex(Id)));
		}
		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
	}
	
	/**
	 *  新增单个个
	 * */
	public void insertHomework(Homework homework) {

		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();
		
		
		ContentValues values = new ContentValues();
		values.put(toId, homework.getToId());
		values.put(toAnswerBitmap, homework.getToAnswerBitmap());
		
		mysql.insert(tableName, null, values);
		mysql.insert(toTableName, null, values);
		
		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
	}
	
	/**
	 * 表中该题目的总页数
	 * */
	public int getNumForTable(String toid) {
		
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		
		Cursor cursor = mysql.rawQuery("SELECT * FROM "
			      + tableName , null);
		int num = cursor.getCount();
		
		mysql.close();
		cursor.close();
		return num;
	}
	
	/**
	 * 更新指定主观题答案
	 * */
	public void updateSubjective(Homework homework) {
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		ContentValues values = new ContentValues();
		values.put(toAnswerBitmap, homework.getToAnswerBitmap());
		
		ContentValues values1 = new ContentValues();
		values1.put(toAnswerBitmap, homework.getToTeacherBitmap());
		System.out.println("更新的页码"+homework.getSubId());

		mysql.update(tableName, values, "id = "+homework.getSubId(), null);
		mysql.update(toTableName, values1, "id = "+homework.getSubId(), null);
		
		mysql.close();
	}
	
	/**
	 * 指定主观题答案页码查询
	 * */
	public Homework searchSubjectiveForPage(String toid, int pageNum) {
		
		Homework myHomework = new Homework();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		Cursor cursor = mysql.rawQuery("SELECT * FROM "
			      + tableName , null);
		if (cursor.moveToPosition(pageNum)) {
			myHomework.setToAnswerBitmap(cursor.getBlob(cursor.getColumnIndex(toAnswerBitmap)));
			myHomework.setToId(toid);
			myHomework.setSubId(cursor.getString(cursor.getColumnIndex(Id)));
		}
		
		mysql.close();
		cursor.close();
		return myHomework;
	}
	
	/**
	 * 获取最终批改图片
	 * */
	public List<Homework> getAllAnswer() {
		List<Homework> myHomeworks = new ArrayList<Homework>();
		
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		Cursor cursor = mysql.query(toTableName, new String[] { toId, toAnswerBitmap},
				null, null, null, null, null, null);
		
		while (cursor.moveToNext()) {
			Homework mHomework = new Homework();
			mHomework.setToTeacherBitmap(cursor.getBlob(1));
			mHomework.setToId(cursor.getString(0));
			myHomeworks.add(mHomework);
		}
		mysql.close();
		cursor.close();
		return myHomeworks;
	}
}
