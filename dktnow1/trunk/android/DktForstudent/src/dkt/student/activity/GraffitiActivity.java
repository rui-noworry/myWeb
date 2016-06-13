package dkt.student.activity;

import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import dkt.student.MyApp;
import dkt.student.R;
import dkt.student.base.UserMsg;
import dkt.student.database.ResourceServer;
import dkt.student.listener.HandwritingListener;
import dkt.student.model.IsDrawBg;
import dkt.student.model.NoteForHan;
import dkt.student.model.Resource;
import dkt.student.view.MainView;
import dkt.student.view.MyImageViewOne;
import dkt.student.view.popu.HuanbiPopu;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.View.OnLongClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.PopupWindow;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.ImageView.ScaleType;
import android.widget.SeekBar.OnSeekBarChangeListener;

public class GraffitiActivity extends Activity implements HandwritingListener{

	private Context context;
	private final int REQUEST_CODE_INSERT_IMAGE_OBJECT = 100;
	private int imgNum = 3000;
	private List<NoteForHan> imageviewList = new ArrayList<NoteForHan>();
	// 写字板
	private MainView myView;
	private int handIndex = 1, mediaIndex = 2;
	private RelativeLayout topRelative, handwritingRelative, mediaRelative, titleRelative;
	private IsDrawBg isDrawBg = new IsDrawBg();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.graffiti);
        
        initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}

	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.note_canvas_view));
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		// TODO Auto-generated method stub
		super.onActivityResult(requestCode, resultCode, data);
		
		if(resultCode==RESULT_OK){
			if(data == null)
				return;
			
			if(requestCode == REQUEST_CODE_INSERT_IMAGE_OBJECT) {    			
				Uri imageFilePath = data.getData();
				String [] proj={MediaStore.Images.Media.DATA}; 
				Cursor cursor = managedQuery(imageFilePath,  proj, null, null, null);   
				int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA); 
				cursor.moveToFirst(); 
				
				String path = cursor.getString(column_index); 
				System.out.println(path);
				
				File f=new File(path);//检查文件是否存在
				if(f.exists()) {
					
					NoteForHan imgNote = new NoteForHan();
					imgNote.setId(imgNum);
					imgNote.setImgWidth(500);
					imgNote.setImgHeight(300);
					imgNote.setImgX(300);
					imgNote.setImgY(200);
					imgNote.setImgPath(path);

					addImageView(imgNote);
					imgNum = imgNum + 1;

				}
			}
		}
	}
	
	private void initView() {
		// TODO Auto-generated method stub
		context = GraffitiActivity.this;
		
		MyApp app = (MyApp) this.getApplication();
		app.setJiePView(findViewById(R.id.note_canvas_view));
		
		topRelative = (RelativeLayout) findViewById(R.id.view_top);
		mediaRelative = (RelativeLayout) findViewById(R.id.view_media);
		handwritingRelative = (RelativeLayout) findViewById(R.id.note_canvas);
		titleRelative = (RelativeLayout) findViewById(R.id.home_title_layout);

		myView = (MainView) findViewById(R.id.note_canvas_view);
		myView.setHandwritingListener(GraffitiActivity.this);
		myView.isNoWrite(true);
		
		RadioButton myRadioButton = (RadioButton) findViewById(R.id.graffiti_write);
		myRadioButton.setChecked(true);
		
		// 长按写字板切换出视图层
		myView.setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub					
				changeLayout(handIndex);
				return true;
			}
		});
		
		 changeLayout(mediaIndex);
	}

	private void addFun() {
		// TODO Auto-generated method stub
		// 返回
		findViewById(R.id.graffiti_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				finish();
			}
		});	
		
		// 保存
		findViewById(R.id.graffiti_save).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				doSavePicture();
			}
		});	
		
		// 本地插图
		findViewById(R.id.graffiti_in).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				callGalleryForInputImage(REQUEST_CODE_INSERT_IMAGE_OBJECT);
			}
		});
		
		// 橡皮
		findViewById(R.id.graffiti_eraser).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myView.isClean(true);
				myView.isNoWrite(true);
			}
		});
		findViewById(R.id.graffiti_eraser).setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub
				View focusView = findViewById(R.id.main_focus);
				EraserPopu myEraserPopu = new EraserPopu(context, focusView, myView);
				myEraserPopu.showPopu();
				return true;
			}
		});

		// 画笔
		findViewById(R.id.graffiti_write).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myView.isClean(false);
				myView.isNoWrite(true);
			}
		});
		findViewById(R.id.graffiti_write).setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub
				View focusView = findViewById(R.id.main_focus);
				HuanbiPopu myHuanbiPopu = new HuanbiPopu(context, focusView, myView);
				myHuanbiPopu.showPopu();
				return true;
			}
		});
	}

	/**
	 * 保存
	 * */
	private void doSavePicture() {
		titleRelative.setVisibility(View.GONE);
		
		final EditText resourceNameEdit = new EditText(this);
    	resourceNameEdit.setTextColor(R.color.black);
    	resourceNameEdit.setBackgroundResource(R.drawable.system_set_edit_bg);
		AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("确定保存？").setIcon(android.R.drawable.ic_dialog_info).setView(resourceNameEdit);
        builder.setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
					
			public void onClick(DialogInterface dialog, int which) {
				
				titleRelative.setVisibility(View.VISIBLE);
				
			}
			
		});
        
        builder.setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int which) {
            	SimpleDateFormat sdf = new SimpleDateFormat( "yyyy-MM-dd_HH-mm-ss", Locale.US);
            	
            	String resourceName = "";
            	String defaultName = "dkt"+ sdf.format(new Date());
            	long currentTimestamp = System.currentTimeMillis();
            	
            	// 如果输入框为空则使用默认名称
            	if("".equals(resourceNameEdit.getText().toString())) {
					resourceName = defaultName;
            	}else{
					resourceName = resourceNameEdit.getText().toString();
            	}
            	String fname = "/sdcard/Dkt/Resource/" + resourceName + ".png"; 
            	View v = findViewById(R.id.note_canvas_view);
            	View view = v.getRootView(); 
            	view.setDrawingCacheEnabled(true); 
            	view.buildDrawingCache();
            	Bitmap bitmap = view.getDrawingCache();
            	if (bitmap != null) {
            		try {
            			FileOutputStream out = new FileOutputStream(fname);
            			bitmap.compress(Bitmap.CompressFormat.PNG, 100, out);
            			
            			Resource resource = new Resource();
            			resource.setResourceName(resourceName);
            			resource.setResourceType(1);
            			resource.setResourcePath(fname);
            			resource.setResourceCreatTime(currentTimestamp);
            			
            			ResourceServer resourceServer = new ResourceServer();
            			resourceServer.insertResource(resource);
            			finish();
					} catch (Exception e) {
						// TODO: handle exception
					}
            	}
             }
        });
        builder.show();
	}
	
	/**
	 * 本地插图
	 * */
	private void callGalleryForInputImage(int nRequestCode){
		try {
			Intent galleryIntent;
			galleryIntent = new Intent(); 
			galleryIntent.setAction(Intent.ACTION_GET_CONTENT);				
			galleryIntent.setType("image/*");
			galleryIntent.setClassName("com.cooliris.media", "com.cooliris.media.Gallery");
			startActivityForResult(galleryIntent, nRequestCode);
		} catch(ActivityNotFoundException e) {
			Intent galleryIntent;
			galleryIntent = new Intent();
			galleryIntent.setAction(Intent.ACTION_GET_CONTENT);
			galleryIntent.setType("image/*");
			startActivityForResult(galleryIntent, nRequestCode);
			e.printStackTrace();
		}		
	}
	
	/**
	 * 在视图层添加本地图片
	 * 
	 * */
	private void addImageView(NoteForHan imgNote) {
		
		MyImageViewOne myImageView;
		Resources r = this.getResources();
		InputStream is = r.openRawResource(R.drawable.cancel);
		BitmapDrawable  bmpDraw = new BitmapDrawable(is);
		Bitmap bmp = bmpDraw.getBitmap();
		
		myImageView = new MyImageViewOne(context, imgNote, bmp, isDrawBg);

		myImageView.setScaleType(ScaleType.FIT_XY);
        RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(
			    imgNote.getImgWidth(), imgNote.getImgHeight());
		layoutParams.setMargins(imgNote.getImgX(), imgNote.getImgY(), 0, 0);
		myImageView.setLayoutParams(layoutParams);
		myImageView.setHandwritingListener(GraffitiActivity.this);
		
		Bitmap myBitmap=BitmapFactory.decodeFile(imgNote.getImgPath());
		myImageView.setImageBitmap(myBitmap);
		myImageView.setId(imgNote.getId());
	
		mediaRelative.addView(myImageView);
		imageviewList.add(imgNote);
	}
	
	/**
     *  写字板和视图层的切换
     * 
     * */
	private void changeLayout(int index) {
		
		topRelative.removeAllViews();
		if(index == mediaIndex){
			isDrawBg.setDrawBg(false);
			topRelative.addView(mediaRelative);
			topRelative.addView(handwritingRelative);
			
		}else if (index == handIndex){
			isDrawBg.setDrawBg(true);
			topRelative.addView(handwritingRelative);
			topRelative.addView(mediaRelative);
		}
	}
	
	@Override
	public void closeActivity() {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void closeImageView(View v) {
		// TODO Auto-generated method stub
		for(int i=0;i<imageviewList.size();i++) {
			if(imageviewList.get(i).getId() == v.getId()) {
				imageviewList.remove(imageviewList.get(i));
			}
		}
		mediaRelative.removeView(v);
		
	}

	@Override
	public void showHandwritingView() {
		// TODO Auto-generated method stub
		if(mediaRelative.getChildCount() > 0) {
			for(int i=0;i<mediaRelative.getChildCount();i++) {
				RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(
						imageviewList.get(i).getImgWidth(), imageviewList.get(i).getImgHeight());
				
				layoutParams.setMargins(imageviewList.get(i).getImgX(), imageviewList.get(i).getImgY(), 0, 0);
				mediaRelative.getChildAt(i).setLayoutParams(layoutParams);
			}
		}
		changeLayout(mediaIndex);
	}
	
	public class EraserPopu {
		
		private PopupWindow popup;
		private Context context;
		private View view;
		TextView  text ;
		MainView myView;
		int paintA = 20;
		int color;
		public EraserPopu(Context context, View view, MainView myView) {
			this.context = context;
			this.view = view;
			this.myView = myView;
		}

		public void clearPopu() {
			popup.dismiss();
		}

		public void showPopu() {
			View view1 = LayoutInflater.from(context).inflate(
					R.layout.z_popu_eraser, null);
			popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
					LayoutParams.WRAP_CONTENT);
			popup.setBackgroundDrawable(new BitmapDrawable());
			popup.setOutsideTouchable(true);
			popup.showAsDropDown(view, 100, -200);
			
			
			final ImageView image = (ImageView) view1.findViewById(R.id.eraser_width_image);

			// 橡皮进度条
			SeekBar seekBar = (SeekBar) view1
					.findViewById(R.id.eraser_width_progress);
			
			String pait = UserMsg.getConfigMsg(context, "painta");

			if (!pait.equals("")) {
				int mypro = Integer.parseInt(pait) * 3;
				seekBar.setProgress(mypro);
				scaleImageMiddleView(image,(float)((float)mypro)/100);
			}else{
				int mypro = 60;
				seekBar.setProgress(mypro);
				scaleImageMiddleView(image,(float)((float)mypro)/100);
			}
			
			seekBar.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {
				//结束拖动
				public void onStopTrackingTouch(SeekBar seekBar) {
					
				}
				//开始拖动
				public void onStartTrackingTouch(SeekBar seekBar) {
					
				}
				//拖动中
				public void onProgressChanged(SeekBar seekBar, int progress,
						boolean fromUser) {
				    if(fromUser){
				    	scaleImageMiddleView(image,(float)((float)progress)/100);
				    	
				    	if((progress/3) < 3) {
				    		paintA = 3;			    		
				    	}else{
				    		paintA = progress/3;
				    	}
				    	myView.setPaintA(paintA);
				    }
					
				}
			});
			
			
			view1.findViewById(R.id.black_btn).setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认清屏")
					.setMessage("确定清除所有数据吗？")
					.setPositiveButton("确定",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									myView.clear();
						        	mediaRelative.removeAllViews();
						        	imageviewList.clear();
								}
							})
					.setNegativeButton("取消",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									dialog.dismiss();

								}
					}).show();
					
				}
			});
			view1.findViewById(R.id.red_btn).setOnClickListener(new OnClickListener() {
				
				@Override 
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认清屏")
					.setMessage("确定清除所有原笔迹数据吗？")
					.setPositiveButton("确定",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									myView.clear();						   
								}
							})
					.setNegativeButton("取消",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									dialog.dismiss();

								}
					}).show();
				}
			});
			view1.findViewById(R.id.blue_btn).setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认清屏")
					.setMessage("确定清除所有视图数据吗？")
					.setPositiveButton("确定",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
						        	mediaRelative.removeAllViews();
						        	imageviewList.clear();
								}
							})
					.setNegativeButton("取消",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									dialog.dismiss();

								}
					}).show();

				}
			});
			
		}

		private void scaleImageMiddleView(ImageView imageView,float scale ){
			Bitmap bp = BitmapFactory.decodeResource(context.getResources(), R.drawable.eraser_circle_bg);
			int width = bp.getWidth();
			int height = bp.getHeight();
			Matrix matrix = new Matrix();
			if(scale < 0.1f){
				scale = 0.1f;
			}
			matrix.postScale(scale, scale);
			bp = Bitmap.createBitmap(bp,0,0,width,height,matrix,true);
			//将上面创建的Bitmap转换成Drawable对象，使得其可以使用在ImageView, ImageButton中
	        BitmapDrawable bmd = new BitmapDrawable(bp);
	        imageView.setImageDrawable(bmd);
			
		}

	}
}
