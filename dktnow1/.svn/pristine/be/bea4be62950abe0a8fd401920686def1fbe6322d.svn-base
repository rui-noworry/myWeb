package dkt.teacher.database;

import java.io.File;
import java.io.IOException;

import dkt.teacher.model.NoteData;
import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;


public class MyNoteHandWritingServer {
	public static final String BITMAP_DATA = "Bitmap";
	private SQLiteDatabase mysql ; 
	public final static String TABLE_NAME = "handwriting";
	public final static String ID = "Id";
	public final static String JESON = "JSONDATA";
	private File path;
	private File f;
	
	public MyNoteHandWritingServer(String pathString) {
		this.path = new File("/sdcard/Dkt/note/dktHandWriting");
		this.f = new File("/sdcard/Dkt/note/dktHandWriting/" + pathString);
	}
	//检查目录以及笔迹数据库文件是否存在并建立相对应的表
	public int checkExists() {
		if (!path.exists()) {
			path.mkdirs();// 创建一个目录
		}
		if (!f.exists()) {
		   try {
				f.createNewFile();//创建文件 
				mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
				
				String str_sql = "CREATE TABLE " + TABLE_NAME + " ("
				            + ID + " integer primary key, "
				            + JESON + " TEXT, "
				            + BITMAP_DATA + " BLOB"				  
				            + ");";
				mysql.execSQL(str_sql);
				mysql.close();
				return 2;
		   }catch (IOException e) {
			   e.printStackTrace();
			   return 3;
		   }
		} 
		return 1;
	}
	
	/**
	 * 获取当前数据库中的总列数
	 * 
	 * */
	public int getPageTotalNum(){
		int pageTotalNum = 0;
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
		try {
			
			Cursor cursor = mysql.rawQuery("SELECT * FROM "
				      + TABLE_NAME, null);
			pageTotalNum = cursor.getCount();
			
			mysql.close();
			cursor.close();
		} catch (Exception e) {
			// TODO: handle exception
			mysql.close();
			e.printStackTrace();
			
		}
		return pageTotalNum;
	}
	
	/**
	 * 获取当前页的原笔迹和视图层数据
	 * 
	 * */
	public NoteData loadPage(int pageNum){
		// 返回的页面数据对象
		NoteData data = new NoteData();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
		try {
			
			Cursor cursor = mysql.rawQuery("SELECT * FROM "
				      + TABLE_NAME, null);
			if (cursor.moveToPosition(pageNum)) {
				data.setBitmapData(cursor.getBlob(cursor.getColumnIndex(BITMAP_DATA)));
				data.setJesonData(cursor.getString(cursor.getColumnIndex(JESON)));
				data.setPageNum(pageNum);
				System.out.println("笔迹数据打开成功");
			}else{
				System.out.println("笔迹数据打开不成功");
			}
			mysql.close();
			cursor.close();
		} catch (Exception e) {
			// TODO: handle exception
			System.out.println("笔迹数据打开不成功");
			mysql.close();
		}
		return data;
	}
	
	/**
	 * 新建一个页面数据  保存这个页面上的原笔迹和视图层数据
	 * 
	 * */
	public void savePage(NoteData data){
		 
		try {
			mysql = SQLiteDatabase.openOrCreateDatabase(f, null);	
			ContentValues values = new ContentValues();	
			values.put(ID, data.getPageNum());
			values.put(BITMAP_DATA, data.getBitmapData());
			values.put(JESON, data.getJesonData());
			mysql.insert(TABLE_NAME, null, values);
			System.out.println("笔迹数据新建成功");
			mysql.close();
		} catch (Exception e) {
			System.out.println("笔迹数据新建不成功");
			mysql.close();
			e.printStackTrace();
			
		}
	}
	
	/**
	 * 更新一个页面数据  保存这个页面上的原笔迹和视图层数据
	 * 
	 * */
	public void updatePage(NoteData data){
		try {
			mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
			ContentValues values = new ContentValues();			
			values.put(BITMAP_DATA, data.getBitmapData());
			values.put(JESON, data.getJesonData());
			mysql.update(TABLE_NAME, values, "id = "+data.getPageNum(), null);
			System.out.println("笔迹数据更新成功"+data.getJesonData());
			mysql.close();
		} catch (Exception e) {
			System.out.println("笔迹数据更新不成功");
			mysql.close();
			e.printStackTrace();
		}
		
	}
}
