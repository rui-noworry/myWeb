package dkt.teacher.view.popu;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import dkt.teacher.R;
import dkt.teacher.database.NoteServer;
import dkt.teacher.listener.NoteAdapterChangeListener;
import dkt.teacher.model.NoteType;
import dkt.teacher.util.ViewUtil;
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
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.PopupWindow;

public class NoteListGroupPopu {
	Context context;
	PopupWindow popup;
	View view;
	EditText newTypeEditText;
	private NoteAdapterChangeListener adapterChangeListener;
	int[] drawables = { R.drawable.z_fenzu_bg1,R.drawable.z_fenzu_bg2};
	int selectDrawable = -1;

	public void setAdapterChangeListener(
			NoteAdapterChangeListener adapterChangeListener) {
		this.adapterChangeListener = adapterChangeListener;
	}

	public NoteAdapterChangeListener getAdapterChangeListener() {
		return adapterChangeListener;
	}

	public NoteListGroupPopu(Context context, View view) {
		this.context = context;
		this.view = view;
	}

	public void showPopu() {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.note_list_group_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
		popup.setOutsideTouchable(true);
		newTypeEditText = (EditText) view1.findViewById(R.id.popup_note_list_group_edit);
		view1.findViewById(R.id.popup_note_btn1).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				popup.dismiss();
				
			}
		});
		view1.findViewById(R.id.popup_note_btn2).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				if(selectDrawable < 0){
					ViewUtil.myToast(context, "请选择分组封面");
				}else{
					NoteServer newType = new NoteServer();
					NoteType notetype = new NoteType();
					notetype.setColumNoteTypeName(newTypeEditText.getText().toString());
					notetype.setColumNotePic(selectDrawable);
					newType.saveType(notetype);
					adapterChangeListener.listAdapterChange();
					popup.dismiss();
				}
				
			}
		});
		
		popup.showAtLocation(view, Gravity.CENTER, 0, 0);

		// setlistview
		setGridView(view1);
	}

	private void setGridView(View view) {
		GridView grid = (GridView) view
				.findViewById(R.id.popup_note_list_group_grid);

		List<HashMap<String, Object>> list = new ArrayList<HashMap<String, Object>>();
		for (int i = 0; i < drawables.length; i++) {
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

				selectDrawable = arg2;
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

	}

	/**
	 * ------------------------适配器结束-------------------------
	 */

}
