package dkt.teacher.database;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import dkt.teacher.MyContants;
import dkt.teacher.model.Resource;

public class ResourceServer {
	
	private File main_path = new File(MyContants.MAIN_PATH);
	private File path = new File(MyContants.RESOURCE_PATH);  // 本地资源文件夹路径
	private File f = new File(MyContants.RESOURCE_DB_NAME);  // 本地资源数据库文件路径
	private SQLiteDatabase database;
	private String tableName = "resource";  // 表名
	private String Id = "resourceId"; // 资源id
	private String netId = "netId"; 
	private String resourceName = "resourceName";  // 资源名称
	private String resourceType = "resourceType"; // 资源类型
	private String resourceCreateTime = "resourceCreateTime";  // 资源创建时间
	private String resourcePath = "resourcePath";  // 资源路径
	
	public ResourceServer() {
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
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();
		
		database.execSQL("CREATE TABLE IF NOT EXISTS " + tableName + " ("
				+ Id + " integer primary key autoincrement, "
				+ resourceName + " varchar(32), "
				+ netId + " integer, "
				+ resourceType + " smallint, "
				+ resourcePath + " varchar(100), " 
				+resourceCreateTime+" bigint );");
		
		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	
	/**
	 *  新增资源
	 * */
	public void insertResource(Resource resource) {

		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(resourceName, resource.getResourceName());
		values.put(netId, resource.getNetId());
		values.put(resourceType, resource.getResourceType());
		values.put(resourcePath, resource.getResourcePath());
		values.put(resourceCreateTime, resource.getResourceCreatTime());
		
		database.insert(tableName, null, values);

		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	
	/**
	 *  更新资源
	 * */
	public void updateResource(Resource resource) {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.beginTransaction();

		ContentValues values = new ContentValues();
		values.put(resourceName, resource.getResourceName());
		values.put(netId, resource.getNetId());
		values.put(resourceType, resource.getResourceType());
		values.put(resourcePath, resource.getResourcePath());
		values.put(resourceCreateTime, resource.getResourceCreatTime());
		database.update(tableName, values, Id+" = ?",
				new String[] { String.valueOf(resource.getResourceId()) });

		database.setTransactionSuccessful();
		database.endTransaction();
		database.close();
	}
	
	/**
	 * 根据网络id获取资源数据
	 * @param myNetId
	 * @return resource
	 * */
	public Resource getMyNetData(int myNetId) {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(tableName, new String[] { Id,
				resourceName, resourceType, resourcePath, resourceCreateTime, netId }, "netId=" + myNetId,
				null, null, null, "resourceCreateTime desc", null);
		Resource resource = new Resource();
		if(0 == cursor.getCount()) {
			database.close();  
			return null;
		}
		cursor.moveToFirst();
		resource.setResourceId(cursor.getInt(0));
		resource.setResourceName(cursor.getString(1));
		resource.setResourceType(cursor.getInt(2));
		resource.setResourcePath(cursor.getString(3));
		resource.setResourceCreatTime(cursor.getLong(4));
		resource.setNetId(cursor.getInt(5));

		cursor.close();
		database.close();
		
		System.out.println("ccjehejadsadalkdsad");
		return resource;
	}
	
	/**
	 * 根据类型获取资源数据
	 * @param type
	 * @return list
	 * */
	public List<Resource> getMyData(int type) {
		List<Resource> list = new ArrayList<Resource>();
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(tableName, new String[] { Id,
				resourceName, resourceType, resourcePath, resourceCreateTime }, "resourceType=" + type,
				null, null, null, "resourceCreateTime desc", null);
		while (cursor.moveToNext()) {
			Resource resource = new Resource();
			resource.setResourceId(cursor.getInt(0));
			resource.setResourceName(cursor.getString(1));
			resource.setResourceType(cursor.getInt(2));
			resource.setResourcePath(cursor.getString(3));
			resource.setResourceCreatTime(cursor.getLong(4));
			list.add(resource);
		}
		cursor.close();
		database.close();
		return list;
	}
	
	/**
	 * 根据资源名称搜索匹配资源
	 * @param searchString
	 * @return list
	 * */
	public List<Resource> getSearchData(String searchString) {
		List<Resource> list = new ArrayList<Resource>();
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		Cursor cursor = database.query(tableName, new String[] { Id,
				resourceName, resourceType, resourcePath, resourceCreateTime }, "resourceName like ?",
				new String[]{"%"+searchString+"%"}, null, null, "resourceCreateTime desc", null);
		while (cursor.moveToNext()) {
			Resource resource = new Resource();
			resource.setResourceId(cursor.getInt(0));
			resource.setResourceName(cursor.getString(1));
			resource.setResourceType(cursor.getInt(2));
			resource.setResourcePath(cursor.getString(3));
			resource.setResourceCreatTime(cursor.getLong(4));
			list.add(resource);
		}
		cursor.close();
		database.close();
		return list;
	}
	
	/**
	 * 删除指定资源
	 * @param id
	 * */
	public void delete(int id) {
		database = SQLiteDatabase.openOrCreateDatabase(f, null);
		database.delete(tableName, Id+" = ?",
				new String[] { String.valueOf(id) });
		database.close();
	}
}
