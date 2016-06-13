package dkt.student.database;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import dkt.student.MyContants;
import dkt.student.model.Homework;
import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

public class MyHomeWorkServer {

	private SQLiteDatabase mysql ; 
	private File main_path = new File(MyContants.MAIN_PATH);
	private File path = new File(MyContants.HOMEWORK_PATH);
	private File f;
	
	private String tableName = "homework";
	private String Id = "id"; // 
	private String actId = "actid"; // 作业id
	private String toId = "toid";  // 题目id
	private String toType = "totype"; // 题目类型
	private String toPath = "topath";  // 题目图片地址
	private String toAnswer = "toanswer";  // 正确答案
	private String answer = "answer";  // 学生答案
	private String toBitmap = "tobitmap"; // 题目二进制图片
	
	private String toTableName = "totable"; // 主观题答案表
	private String toAnswerBitmap = "toanswerbitmap";
	
	
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
				+ actId + " text, "
				+ toId + " text, "
				+ toType + " text, "
				+ toPath + " text, " 
				+ answer + " text, " 
				+ toBitmap + " BLOB, " 
				+toAnswer+" text );");
		mysql.execSQL("CREATE TABLE IF NOT EXISTS " + toTableName + " ("
				+ Id + " integer primary key autoincrement, "
				+ toId + " text, "
				+toAnswerBitmap+" BLOB );");
		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
	}
	
	/**
	 *  题目表新增
	 * */
	public void insertHomework(Homework homework) {

		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(actId, homework.getActId());
		values.put(toId, homework.getToId());
		values.put(toType, homework.getToType());
		values.put(toPath, homework.getToPath());
		values.put(answer, homework.getAnswer());
		values.put(toBitmap, homework.getToBitmap());
		values.put(toAnswer, homework.getToAnswer());
		
		mysql.insert(tableName, null, values);

		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
	}
	
	/**
	 * 判断题目表中题目的数量
	 * */
	public int getNumForTable() {
		
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = mysql.rawQuery("SELECT * FROM "
			      + toTableName, null);
		int num = cursor.getCount();
		
		mysql.close();
		cursor.close();
		return num;
	}
	
	/**
	 *  主观题答案新增新增
	 * */
	public void insertSubjective(Homework homework) {

		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(toAnswerBitmap, homework.getToAnswerBitmap());
		values.put(toId, homework.getToId());
		values.put(Id, homework.getSubId());
		
		mysql.insert(toTableName, null, values);
		System.out.println("insertSubjective===="+homework.getToId());
		mysql.setTransactionSuccessful();
		mysql.endTransaction();
		mysql.close();
	}
	
	/**
	 * 更新客观题中学生做的答案
	 * */
	public void updateHomework(Homework homework) {
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		ContentValues values = new ContentValues();
		values.put(answer, homework.getAnswer());
		values.put(toId, homework.getToId());
		System.out.println(":==========homework.getAnswer()="+homework.getAnswer());

		mysql.update(tableName, values, "toid = "+homework.getToId(), null);
		mysql.close();
	}
	
	/**
	 * 更新指定主观题答案
	 * */
	public void updateSubjective(Homework homework) {
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		ContentValues values = new ContentValues();
		values.put(toAnswerBitmap, homework.getToAnswerBitmap());
		values.put(toId, homework.getToId());
		values.put(Id, homework.getSubId());
		System.out.println("updateSubjective===="+homework.getSubId());
		mysql.update(toTableName, values, "id = "+homework.getSubId(), null);
		mysql.close();
	}
	
	/**
	 * 获取指定客观题的答案
	 * */
	public Homework getToAnswerForId(String toid) {
		Homework myHomework = new Homework();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		Cursor cursor = mysql.query(tableName, new String[] { toId, answer},
				"toid=" + toid, null, null, null, null, null);
		if(0 == cursor.getCount()) {
			mysql.close();
			cursor.close();
			return null;
		}else{
			cursor.moveToFirst();
			myHomework.setAnswer(cursor.getString(1));
			myHomework.setToId(cursor.getString(0));
		}
		mysql.close();
		cursor.close();
		return myHomework;
	}
	
	/**
	 * 获取所有客观题答案
	 * */
	public List<Homework> getToAnswerForAll() {
		List<Homework> myHomeworks = new ArrayList<Homework>();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		Cursor cursor = mysql.query(tableName, new String[] { toId, answer},
				null, null, null, null, null, null);
		while (cursor.moveToNext()) {
			Homework mHomework = new Homework();
			mHomework.setAnswer(cursor.getString(1));
			System.out.println(":==========cursor.getString(1)="+cursor.getString(1));
			mHomework.setToId(cursor.getString(0));
			myHomeworks.add(mHomework);
		}
		mysql.close();
		cursor.close();
		return myHomeworks;
	}
	
	/**
	 * 指定主观题答案页码查询
	 * */
	public Homework searchSubjectiveForPage(String toid, int pageNum) {
		
		Homework myHomework = new Homework();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		

		Cursor cursor = mysql.rawQuery("SELECT * FROM "
			      + toTableName + " WHERE toid=" + toid, null);
		
		if (cursor.moveToPosition(pageNum)) {
			myHomework.setToAnswerBitmap(cursor.getBlob(cursor.getColumnIndex(toAnswerBitmap)));
			myHomework.setToId(toid);
			myHomework.setSubId(cursor.getString(cursor.getColumnIndex(Id)));
		}else{
			mysql.close();
			cursor.close();
			return null;
		}
		mysql.close();
		cursor.close();
		return myHomework;
	}
	
	/**
	 * 获取主观题所有答案查询
	 * */
	public List<Homework> searchSubjectiveForAll() {
		List<Homework> myHomeworks = new ArrayList<Homework>();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		Cursor cursor = mysql.query(toTableName, new String[] { toId, toAnswerBitmap},
				null, null, null, null, null, null);
		while (cursor.moveToNext()) {
			Homework mHomework = new Homework();
			mHomework.setToAnswerBitmap(cursor.getBlob(1));
			mHomework.setToId(cursor.getString(0));
			myHomeworks.add(mHomework);
		}
		mysql.close();
		cursor.close();
		return myHomeworks;
	}
	
	/**
	 * 指定主观题答案总页码查询
	 * */
	public int searchSubjectiveForNum(String toid) {
		int num = 0;
		
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = mysql.query(toTableName, new String[] { Id, toId, toAnswerBitmap},
				"toid=" + toid, null, null, null, null, null);
		num = cursor.getCount();
		mysql.close();
		cursor.close();
		
		return num;
	}
}
