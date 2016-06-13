package dkt.teacher.activity;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.database.NoteServer;
import dkt.teacher.listener.NoteAdapterChangeListener;
import dkt.teacher.model.Note;
import dkt.teacher.model.NoteType;
import dkt.teacher.util.ViewUtil;
import dkt.teacher.view.popu.NoteListGroupPopu;
import dkt.teacher.view.popu.NoteListNewPopu;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Looper;
import android.os.MessageQueue.IdleHandler;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemLongClickListener;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

public class NoteListActivity extends Activity implements NoteAdapterChangeListener{

	private NoteAdapter listViewAdapter;
	private NoteAdapter gridviewAdapter;
	private Context context;
	private List<Note> notelist = new ArrayList<Note>();
	
	int[] drawablesNews = { R.drawable.z_biji_bg1, R.drawable.z_biji_bg2,
			R.drawable.z_biji_bg3 };
	int[] drawablesGroup = { R.drawable.z_fenzu_bg1,R.drawable.z_fenzu_bg2};
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		this.setContentView(R.layout.note_list);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
		context = NoteListActivity.this;
		
		addFun();

		setListView();
		setGridView();
	}
	
	private void addFun() {
		
		//新建分组
		findViewById(R.id.note_list_group_btn).setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				NoteListGroupPopu groupPop = new NoteListGroupPopu(NoteListActivity.this, v);
				groupPop.showPopu();
				groupPop.setAdapterChangeListener(NoteListActivity.this);
			}
		});
		//新建笔记
		findViewById(R.id.note_list_new_btn).setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(final View v) {
			
				NoteListNewPopu newPop = new NoteListNewPopu(NoteListActivity.this, v);
				
				newPop.showPopu();
				newPop.setAdapterChangeListener(NoteListActivity.this);

			}
		});
		//搜索
		findViewById(R.id.note_search_bt).setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				EditText searchStringEditText = (EditText)findViewById(R.id.note_list_search_edit);
				if("".equals(searchStringEditText.getText().toString())){
					ViewUtil.myToast(NoteListActivity.this, getString(R.string.search_content));
				}else{
					List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
					notelist.clear();
					NoteServer notes = new NoteServer();
					notelist = notes.getSearchData(searchStringEditText.getText().toString().trim());
					
					for (int i = 0; i < notelist.size(); i++) {
						HashMap<String, Object> map = new HashMap<String, Object>();
						map.put("name", notelist.get(i).getNoteName());
						map.put("time", notelist.get(i).getNoteCreateTime());
						map.put("pic", getDrawableNew(notelist.get(i).getNotePic()));
						list.add(map);
					}
					gridviewAdapter.list = list;
					gridviewAdapter.notifyDataSetChanged();
				}
				
			}
		});
	findViewById(R.id.note_list_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});
	}

	private void setListView() {
		ListView listView = (ListView) findViewById(R.id.note_list_listview);
		List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
		
		NoteServer types = new NoteServer();
		final List<NoteType> typeslist = types.getTypeData();
		for (int i = 0; i < typeslist.size(); i++) {
			HashMap<String, Object> map = new HashMap<String, Object>();
			map.put("name", typeslist.get(i).getColumNoteTypeName());
			map.put("pic", getDrawableGroup(typeslist.get(i).getColumNotePic()));
			list.add(map);
		}
		listViewAdapter = new NoteAdapter(NoteListActivity.this, list,
				MyContants.NOTE_LIST_LISTVIE);
		listView.setAdapter(listViewAdapter);
		listView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				if(arg2 == 0){    
					setGridView();
				}else{
					List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
					
					NoteServer notes = new NoteServer();
					final List<Note> notelist = notes.getMyData(typeslist.get(arg2).getColumNoteTypeId());
					
					for (int i = 0; i < notelist.size(); i++) {
						HashMap<String, Object> map = new HashMap<String, Object>();
						map.put("name", notelist.get(i).getNoteName());
						map.put("time", notelist.get(i).getNoteCreateTime());
						map.put("pic", getDrawableNew(notelist.get(i).getNotePic()));
						list.add(map);
					}
					gridviewAdapter.list = list;
					gridviewAdapter.notifyDataSetChanged();
				}
			}
		});

	}
	/**
	 * 笔记的的图片
	 * @param index
	 * @return
	 */
	private int getDrawableNew(int index){
		if(index < 0 ){
			index = 0;
		}
		if(index >=  drawablesNews.length){
			index = drawablesNews.length -1;
		}
		
		return drawablesNews[index];
	}
	
	/**
	 * 分组的图片
	 * @param index
	 * @return
	 */
	private int getDrawableGroup(int index){
		if(index < 0 ){
			index = 0;
		}
		if(index >=  drawablesGroup.length){
			index = drawablesGroup.length -1;
		}
		
		return drawablesGroup[index];
	}

	private void setGridView() {
		GridView gridView = (GridView) findViewById(R.id.note_list_gridview);
		List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
		notelist.clear();
		NoteServer notes = new NoteServer();
		notelist = notes.getData();
		
		for (int i = 0; i < notelist.size(); i++) {
			HashMap<String, Object> map = new HashMap<String, Object>();
			map.put("name", notelist.get(i).getNoteName());
			map.put("time", notelist.get(i).getNoteCreateTime());
			map.put("pic", getDrawableNew(notelist.get(i).getNotePic()));
			System.out.println(i+"===="+notelist.get(i).getNoteCreateTime()+"=="+notelist.get(i).getNoteName());

			list.add(map);
		}
		gridviewAdapter = new NoteAdapter(NoteListActivity.this, list,
				MyContants.NOTE_LIST_GRIDVIEW);
		gridView.setAdapter(gridviewAdapter);
		gridView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				Intent intent = new Intent(NoteListActivity.this,
						NoteShowActivity.class);
				intent.putExtra("notepath", notelist.get(arg2).getNotePath());
				intent.putExtra("noteid", notelist.get(arg2).getNoteId());
				
				
				Note note = new Note();
				note.setNoteName(notelist.get(arg2).getNoteName());
				note.setNoteType(notelist.get(arg2).getNoteType());
				note.setNoteUpdateTime(System.currentTimeMillis());
				note.setNoteId(notelist.get(arg2).getNoteId());
				NoteServer updateUpdateTime = new NoteServer();
				updateUpdateTime.update(note);
				
				startActivity(intent);
			}
		});
		gridView.setOnItemLongClickListener(new OnItemLongClickListener() {

			@Override
			public boolean onItemLongClick(AdapterView<?> arg0, View arg1,
					int arg2, long arg3) {
				// TODO Auto-generated method stub
				ViewUtil.myToast(NoteListActivity.this, "长按事件点击  点击了第"+arg2+"子选项");

				return true;
			}
		});
	}

	/**
	 * ========================适配器开始========================
	 */
	class NoteAdapter extends BaseAdapter {
		Context context;
		List<HashMap<String, Object>> list;
		Holder holder;
		String tag;

		public NoteAdapter(Context context, List<HashMap<String, Object>> list,
				String tag) {
			this.context = context;
			this.list = list;
			this.tag = tag;
		}

		@Override
		public int getCount() {
			// TODO Auto-generated method stub
			return list.size();
		}

		@Override
		public Object getItem(int arg0) {
			// TODO Auto-generated method stub
			return null;
		}

		@Override
		public long getItemId(int arg0) {
			// TODO Auto-generated method stub
			return 0;
		}

		@Override
		public View getView(int arg0, View arg1, ViewGroup arg2) {
			// TODO Auto-generated method stub
			View view = null;
			if (tag.equals(MyContants.NOTE_LIST_LISTVIE)) {
				view = getListView(arg0, arg1, arg2);
			} else if (tag.equals(MyContants.NOTE_LIST_GRIDVIEW)) {
				view = getGridView(arg0, arg1, arg2);
			}

			return view;
		}

		private View getListView(int arg0, View arg1, ViewGroup arg2) {
			if (arg1 == null) {
				holder = new Holder();
				arg1 = LayoutInflater.from(context).inflate(
						R.layout.note_list_listview_item, null);
				holder.name = (TextView) arg1
						.findViewById(R.id.note_list_listview_text);
				holder.image = (ImageView) arg1
						.findViewById(R.id.note_list_listview_image);
				arg1.setTag(holder);

			} else {
				holder = (Holder) arg1.getTag();

			}
			String nameStr = (String) list.get(arg0).get("name");
			holder.name.setText(nameStr);
			int imageDrawable = Integer.valueOf(list.get(arg0).get("pic").toString());
			if(imageDrawable >0){
				holder.image.setImageResource(imageDrawable);
			}

			return arg1;
		}

		private View getGridView(int arg0, View arg1, ViewGroup arg2) {
			if (arg1 == null) {
				holder = new Holder();
				arg1 = LayoutInflater.from(context).inflate(
						R.layout.note_list_gridview_item, null);
				holder.name = (TextView) arg1
						.findViewById(R.id.note_list_gridview_name);
				holder.time = (TextView) arg1
						.findViewById(R.id.note_list_gridview_time);
				holder.image = (ImageView) arg1
						.findViewById(R.id.note_list_gridview_image);
				arg1.setTag(holder);

			} else {
				holder = (Holder) arg1.getTag();

			}
			String nameStr = (String) list.get(arg0).get("name");
			if(arg0 == 0){
				holder.name.setText(nameStr+getString(R.string.new_note));
			}else{
				holder.name.setText(nameStr);
			}
			
			String re_StrTime = null;

			SimpleDateFormat sdf = new SimpleDateFormat("yyyy.MM.dd");
			long unixLong = 0; 
			unixLong = Long.parseLong((String) list.get(arg0).get("time").toString()); 
			re_StrTime = sdf.format(unixLong);			
			holder.time.setText(re_StrTime);
			
			int imageDrawable = Integer.valueOf(list.get(arg0).get("pic").toString());
			if(imageDrawable >0){
				holder.image.setImageResource(imageDrawable);
			}
			return arg1;
		}

	}

	class Holder {
		ImageView image;
		TextView name, time;

	}


	@Override
	public void listAdapterChange() {
		List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
		NoteServer types = new NoteServer();
		final List<NoteType> typeslist = types.getTypeData();
		for (int i = 0; i < typeslist.size(); i++) {
			HashMap<String, Object> map = new HashMap<String, Object>();
			map.put("name", typeslist.get(i).getColumNoteTypeName());
			map.put("pic", getDrawableGroup(typeslist.get(i).getColumNotePic()));
			list.add(map);
		}
		listViewAdapter.list = list;
		listViewAdapter.notifyDataSetChanged();
	}

	@Override
	public void gridAdapterChange() {
		// TODO Auto-generated method stub
		List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
		notelist.clear();
		NoteServer notes = new NoteServer();
		notelist = notes.getData();
		
		for (int i = 0; i < notelist.size(); i++) {
			HashMap<String, Object> map = new HashMap<String, Object>();
			map.put("name", notelist.get(i).getNoteName());
			map.put("time", notelist.get(i).getNoteCreateTime());
			map.put("pic", getDrawableNew(notelist.get(i).getNotePic()));
			list.add(map);
		}
		
		gridviewAdapter.list = list;
		gridviewAdapter.notifyDataSetChanged();
	}

	/**
	 * ------------------------适配器结束-------------------------
	 */

}
