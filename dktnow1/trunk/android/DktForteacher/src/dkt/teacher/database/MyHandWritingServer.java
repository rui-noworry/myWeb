package dkt.teacher.database;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import dkt.teacher.model.NoteData;
import dkt.teacher.model.SuData;
import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;


public class MyHandWritingServer {
	
	private SQLiteDatabase mysql ; 
	
	// 数据库表名
	public final static String TABLE_NAME = "handwriting";
	public final static String SU_TABLE_NAME = "subviews";
	
	// 原笔迹表列名
	public final static String ID = "ha_id";
	public static final String BITMAP_DATA = "ha_bitmap";
	
	// 视图层表列名
	public final static String SU_ID = "vi_id";
	public final static String SU_TYPE = "type";
	public final static String SU_TRAN = "re_transpath";
	public final static String SU_SAVE = "re_savepath";
	public final static String SU_TITLE = "re_title";
	public final static String SU_URL = "localURL";
	public final static String SU_X = "x";
	public final static String SU_Y = "y";
	public final static String SU_WIDTH = "width";
	public final static String SU_HEIGHT = "height";
	public final static String SU_TAG = "tag";
	public final static String SU_BITMAP = "vi_bitmap";
	
	private File path;
	private File f;
	
	public MyHandWritingServer(String pathString) {
		this.path = new File("/sdcard/Dkt");
		this.f = new File("/sdcard/Dkt/" + pathString);
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
				
				String str_sql = "CREATE TABLE IF NOT EXISTS " + TABLE_NAME + " ("
				            + ID + " integer primary key, "
				            + BITMAP_DATA + " BLOB"				  
				            + ");";
				mysql.execSQL(str_sql);
				
				mysql.execSQL("CREATE TABLE IF NOT EXISTS " + SU_TABLE_NAME + " ("
						+ SU_ID + " integer primary key autoincrement, "
						+ ID + " integer, " 
						+ SU_TYPE + " integer, "
						+ SU_TRAN+ " text, "
						+ SU_SAVE+ " text, "
						+ SU_TITLE + " text, "
						+ SU_URL + " text, " 
						+ SU_X + " text, " 
						+ SU_Y + " text, " 
						+ SU_WIDTH + " text, " 
						+ SU_HEIGHT + " text, " 
						+ SU_TAG + " text, " 
						+SU_BITMAP+" BLOB );");
				
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
	 * 获取当前原笔迹总页数
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
	 * 获取当前页的原笔迹数据
	 * 
	 * */
	public NoteData loadPage(int pageNum){
		// 返回的页面数据对象
		NoteData data = new NoteData();
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
		try {
			
			Cursor cursor = mysql.rawQuery("SELECT * FROM "
				      + TABLE_NAME, null);
			if (cursor.moveToPosition(pageNum-1)) {
				data.setBitmapData(cursor.getBlob(cursor.getColumnIndex(BITMAP_DATA)));
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
	 * 获取当前页的视图层数据
	 * 
	 * */
	public List<SuData> getPageSuData(int pageNum) {
		List<SuData> suDataList = new ArrayList<SuData>();
		
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		
		try {
		
			Cursor cursor = mysql.query(SU_TABLE_NAME, new String[] { SU_ID,
					ID, SU_TYPE, SU_TRAN, SU_SAVE, SU_TITLE, SU_URL, SU_X, SU_Y, SU_WIDTH, SU_HEIGHT, SU_TAG, SU_BITMAP}, 
					"ha_id="+pageNum,
					null, null, null, null);
			while (cursor.moveToNext()) {
				if(1 == cursor.getInt(2)){
					SuData suData = new SuData();
					suData.setSuId(cursor.getInt(0));
					suData.setHaId(cursor.getInt(1));
					suData.setSuType(cursor.getInt(2));
					suData.setSuTran(cursor.getString(3));
					suData.setSuPath(cursor.getString(4));
					suData.setSuTitle(cursor.getString(5));
					suData.setSuUrl(cursor.getString(6));
					suData.setSuX(cursor.getInt(7));
					suData.setSuY(cursor.getInt(8));
					suData.setSuWidth(cursor.getInt(9));
					suData.setSuHeight(cursor.getInt(10));
					suData.setSuTag(cursor.getString(11));
					suData.setSuBitmap(cursor.getBlob(12));
		            suDataList.add(suData);
				}
				
			}
			mysql.close();
			cursor.close();
		} catch (Exception e) {
			// TODO: handle exception
			System.out.println("笔迹数据打开不成功");
			mysql.close();
		}
		
		return suDataList;
	}
	
	/**
	 * 新建一个页面数据  保存这个页面上的原笔迹
	 * 
	 * */
	public void savePage(NoteData data){
		 
		try {
			mysql = SQLiteDatabase.openOrCreateDatabase(f, null);	
			ContentValues values = new ContentValues();	
			values.put(ID, data.getPageNum());
			values.put(BITMAP_DATA, data.getBitmapData());
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
	 * 新建页面上一个视图层数据
	 * 
	 * */
	public void savePageSu(SuData suData) {
		try {
			mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
			ContentValues suValues = new ContentValues();	
//			suValues.put(SU_ID, suData.getSuId());
			suValues.put(ID, suData.getHaId());
			suValues.put(SU_TYPE, suData.getSuType());
			suValues.put(SU_TRAN, suData.getSuTran());
			suValues.put(SU_SAVE, suData.getSuPath());
			suValues.put(SU_TITLE, suData.getSuTitle());
			suValues.put(SU_URL, suData.getSuUrl());
			suValues.put(SU_X, suData.getSuX());
			suValues.put(SU_Y, suData.getSuY());
			suValues.put(SU_WIDTH, suData.getSuWidth());
			suValues.put(SU_HEIGHT, suData.getSuHeight());
			suValues.put(SU_TAG, suData.getSuTag());
			suValues.put(SU_BITMAP, suData.getSuBitmap());
			
			mysql.insert(SU_TABLE_NAME, null, suValues);
			mysql.close();
		} catch (Exception e) {
			// TODO: handle exception
			mysql.close();
			e.printStackTrace();
		}
	}
	/**
	 * 更新保存这个页面上的原笔迹和视图层数据
	 * 
	 * */
	public void updatePage(NoteData data, List<SuData> suDatas){
		try {
			mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
			ContentValues values = new ContentValues();			
			values.put(BITMAP_DATA, data.getBitmapData());
			mysql.update(TABLE_NAME, values, "ha_id = "+data.getPageNum(), null);			
			
			for(int i=0;i<suDatas.size();i++){
				ContentValues suValues = new ContentValues();			
				suValues.put(SU_ID, suDatas.get(i).getSuId());
				suValues.put(ID, suDatas.get(i).getHaId());
				suValues.put(SU_TYPE, suDatas.get(i).getSuType());
				suValues.put(SU_TRAN, suDatas.get(i).getSuTran());
				suValues.put(SU_SAVE, suDatas.get(i).getSuPath());
				suValues.put(SU_TITLE, suDatas.get(i).getSuTitle());
				suValues.put(SU_URL, suDatas.get(i).getSuUrl());
				suValues.put(SU_X, suDatas.get(i).getSuX());
				suValues.put(SU_Y, suDatas.get(i).getSuY());
				suValues.put(SU_WIDTH, suDatas.get(i).getSuWidth());
				suValues.put(SU_HEIGHT, suDatas.get(i).getSuHeight());
				suValues.put(SU_TAG, suDatas.get(i).getSuTag());
				suValues.put(SU_BITMAP, suDatas.get(i).getSuBitmap());
				mysql.update(SU_TABLE_NAME, suValues, "vi_id = "+suDatas.get(i).getSuId(), null);
			}
			
			System.out.println("笔迹数据更新成功");
			mysql.close();
		} catch (Exception e) {
			System.out.println("笔迹数据更新不成功");
			mysql.close();
			e.printStackTrace();
		}
		
	}
	
	/**
	 * 删除一个视图层数据
	 * 
	 * */
	public void deleteSu(int suId) {
		
		try {
			mysql = SQLiteDatabase.openOrCreateDatabase(f, null); 
			mysql.delete(SU_TABLE_NAME, SU_ID+" = ?",
					new String[] { String.valueOf(suId) });
			mysql.close();
		} catch (Exception e) {
			mysql.close();
			e.printStackTrace();
		}
	}
	
	/**
	 * 删除所有视图
	 * */
	public void deleteAllSu(int pageNum) {
		mysql = SQLiteDatabase.openOrCreateDatabase(f, null);
		mysql.beginTransaction();
		try {
		
			Cursor cursor = mysql.query(SU_TABLE_NAME, new String[] { SU_ID,
					ID, SU_TYPE, SU_TRAN, SU_SAVE, SU_TITLE, SU_URL, SU_X, SU_Y, SU_WIDTH, SU_HEIGHT, SU_TAG, SU_BITMAP}, 
					"ha_id="+pageNum,
					null, null, null, null);
			
			while (cursor.moveToNext()) {
				if(1 == cursor.getInt(2)){
					mysql.delete(SU_TABLE_NAME, SU_ID+" = ?",
							new String[] { String.valueOf(cursor.getInt(0)) });
				}
				
			}
			mysql.setTransactionSuccessful();
			mysql.endTransaction();
			mysql.close();
			cursor.close();
			
		} catch (Exception e) {
			// TODO: handle exception
			System.out.println("笔迹数据打开不成功");
			mysql.close();
		}
	}
}
