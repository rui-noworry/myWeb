package dkt.student.view.popu;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import dkt.student.R;
import dkt.student.activity.NoteListActivity;
import dkt.student.database.NoteServer;
import dkt.student.listener.NoteAdapterChangeListener;
import dkt.student.model.Note;
import dkt.student.model.NoteType;
import dkt.student.util.ViewUtil;
import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.graphics.drawable.BitmapDrawable;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.Spinner;
import android.widget.TextView;

public class NoteListNewPopu {
	Context context;
	PopupWindow popup;
	View view;
	Button noteTypeButton;
	EditText newNoteEditText;
	int NoteType = 0;
	private NoteAdapterChangeListener adapterChangeListener;
	int[] drawables = { R.drawable.z_biji_bg1, R.drawable.z_biji_bg2,
			R.drawable.z_biji_bg3 };
	int selectDrawable = -1;

	public void setAdapterChangeListener(
			NoteAdapterChangeListener adapterChangeListener) {
		this.adapterChangeListener = adapterChangeListener;
	}

	public NoteAdapterChangeListener getAdapterChangeListener() {
		return adapterChangeListener;
	}

	public NoteListNewPopu(Context context, View view) {
		this.context = context;
		this.view = view;
	}

	public void showPopu() {
		
		if(!((Activity) context).isFinishing()) {
			System.out.println("=====");
		}
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.note_list_new_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
		popup.setOutsideTouchable(true);
		newNoteEditText = (EditText) view1
				.findViewById(R.id.popup_note_list_new_edit);
		noteTypeButton = (Button) view1
				.findViewById(R.id.popup_note_group_spinner);
//		final NoteServer notes = new NoteServer();
//		List<NoteType> typeslist = notes.getTypeData();
//		String[] typeNames = new String[typeslist.size()];
//		for (int i = 0; i < typeslist.size(); i++) {
//			typeNames[i] = typeslist.get(i).getColumNoteTypeName();
//			System.out.println(typeslist.get(i).getColumNoteTypeName());
//		}
		noteTypeButton.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				showNoteTypeDialog();
			}
		});
//		ArrayAdapter<String> adapter = new ArrayAdapter<String>(context,
//				android.R.layout.simple_spinner_item, typeNames);
//		
//		adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
//		noteTypeSpinner.setAdapter(adapter);
//		
//		System.out.println("===="+noteTypeSpinner.getContext());
		
		
		view1.findViewById(R.id.popup_note_btn2).setOnClickListener(
				new OnClickListener() {

					@Override
					public void onClick(View v) {
						// TODO Auto-generated method stub
						if(selectDrawable < 0){
							ViewUtil.myToast(context, "请选择笔记封面");
						}else{
							NoteServer notes = new NoteServer();
							Note note = new Note();
							note.setNoteName(newNoteEditText.getText().toString());
//							note.setNoteType((int) noteTypeSpinner
//									.getSelectedItemId());
							note.setNoteType(NoteType);
							note.setNoteCreateTime(System.currentTimeMillis());
							note.setNoteUpdateTime(System.currentTimeMillis());
							note.setNotePath(System.currentTimeMillis() + ".db");
							note.setNotePic(selectDrawable);
							notes.save(note);
							adapterChangeListener.gridAdapterChange();
							popup.dismiss();
						}

					}
				});

		popup.showAtLocation(view, Gravity.CENTER, 0, 0);

		// setlistview
		setGridView(view1);
	}

	/**
	 * 弹出分组选择
	 * */
	private void showNoteTypeDialog() {
		
		NoteServer notes = new NoteServer();
		final List<NoteType> typeslist = notes.getTypeData();
		
		
		LayoutInflater inflater = (LayoutInflater) context
		.getSystemService(context.LAYOUT_INFLATER_SERVICE);
		View layout = inflater.inflate(R.layout.notedialog, null);
		final Dialog dialog = new Dialog(context,R.style.dialog);
		dialog.setContentView(layout);
		dialog.setCancelable(true);
		
		ListView myListView = (ListView) layout.findViewById(R.id.note_dialog_listview);
		NoteDialogAdapter myDialogAdapter = new NoteDialogAdapter(context, typeslist);
		myListView.setAdapter(myDialogAdapter);
		myListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				NoteType = arg2;
				noteTypeButton.setText(typeslist.get(arg2).getColumNoteTypeName());
				dialog.dismiss();
			}
		});
		dialog.show();
	}
	
	
	private void setGridView(View view) {
		GridView grid = (GridView) view
				.findViewById(R.id.popup_note_list_new_grid);

		List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
		for (int i = 0; i < 3; i++) {
			HashMap<String, Object> map = new HashMap<String, Object>();
			map.put("src", drawables[i]);
			list.add(map);
		}
		NoteAdapter adapter = new NoteAdapter(context, list);
		grid.setAdapter(adapter);
		grid.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				selectDrawable =arg2;
			}
		});
	}
	
	class NoteDialogAdapter extends BaseAdapter {
		Context context;
		List<NoteType> list;
		Holder holder;

		public NoteDialogAdapter(Context context, List<NoteType> list) {
			this.context = context;
			this.list = list;
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
			if (arg1 == null) {
				holder = new Holder();
				arg1 = LayoutInflater.from(context).inflate(
						R.layout.notedialog_item, null);
				holder.noteType = (TextView) arg1
						.findViewById(R.id.notetype_name);
				arg1.setTag(holder);

			} else {
				holder = (Holder) arg1.getTag();

			}
			holder.noteType.setText(list.get(arg0).getColumNoteTypeName());
			return arg1;

		}

	}
	
	/**
	 * ========================适配器开始========================
	 */
	class NoteAdapter extends BaseAdapter {
		Context context;
		List<HashMap<String, Object>> list;
		Holder holder;

		public NoteAdapter(Context context, List<HashMap<String, Object>> list) {
			this.context = context;
			this.list = list;
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
			if (arg1 == null) {
				holder = new Holder();
				arg1 = LayoutInflater.from(context).inflate(
						R.layout.note_list_new_popu_item, null);
				holder.image = (ImageView) arg1
						.findViewById(R.id.note_list_new_popup_image);
				arg1.setTag(holder);

			} else {
				holder = (Holder) arg1.getTag();

			}
			Integer src = Integer.valueOf(list.get(arg0).get("src").toString());
			holder.image.setImageResource(src);
			return arg1;

		}

	}

	class Holder {
		ImageView image;
		TextView noteType;
	}

	/**
	 * ------------------------适配器结束-------------------------
	 */

}
