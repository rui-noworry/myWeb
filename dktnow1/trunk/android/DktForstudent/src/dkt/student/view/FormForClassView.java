package dkt.student.view;

import java.util.HashMap;

import dkt.student.listener.FormListener;
import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;

public class FormForClassView extends View{

	private int firstX = 15; // 起始点x
	private int firstY = 65; // 起始点y
	private int secondX = 95; // 第二点x
	private int secondY = 115; // 第二点y
	private int widthNum = 8;  // 列
	private int heightNum = 8; // 行
	private int secondSideX = 150; // 第二列的宽
	private int sideY = 50; // 行高
	private int firstSidesX = 80; // 第一列的宽
	
	public HashMap<String, String> list = new HashMap<String, String>();
	FormListener myFormListener;
	
	// 点击事件监听
	public FormListener getFormListener() {
		return myFormListener;
	}
	public void setFormListener(FormListener myFormListener) {
		this.myFormListener = myFormListener;
	}
	
	public FormForClassView(Context context, AttributeSet attrs) {
		super(context, attrs);
		// TODO Auto-generated constructor stub
	}
	
	public void setList(HashMap<String, String> list) {
		this.list = list;

	}
	@Override
	protected void onDraw(Canvas canvas) {
		// TODO Auto-generated method stub
		super.onDraw(canvas);
		drawForm(canvas);
	}
	
	private void drawForm(Canvas canvas) {
		Paint paint = new Paint();

		paint.setAntiAlias(true);
		paint.setColor(Color.BLACK);
		paint.setStyle(Paint.Style.STROKE);
		paint.setStrokeWidth(2);
		paint.setStyle(Paint.Style.FILL);
		

		paint.setColor(Color.BLACK);
		paint.setStyle(Paint.Style.STROKE);
		int cellX, cellY, cellBX, cellBY;
		for (int i = 0; i < widthNum; i++)
			
			for (int j = 0; j < heightNum; j++) {
				if(i == 0) { // 如果是第一列绘制第一列的宽度
					cellX = firstX + i * firstSidesX;
					cellY = firstY + j * sideY;
					cellBX = firstX + (i + 1) * firstSidesX;
					cellBY = firstY + (j + 1) * sideY;
				}else{
					
					cellX = secondX + (i-1) * secondSideX;
					cellY = secondY + (j-1) * sideY;
					cellBX = secondX + i * secondSideX;
					cellBY = secondY + j  * sideY;
				}

				canvas.drawRect(cellX, cellY, cellBX, cellBY, paint);
				int cellsNum = i + j * widthNum;
				if(cellsNum % widthNum != 0) { 
					if(list.containsKey(String.valueOf(cellsNum))) {
						drawCellColor(canvas, cellX, cellY, cellBX, cellBY, 0xffADFF2F);
						drawCellText(canvas, cellX, cellY, cellBX, cellBY, list.get(String.valueOf(cellsNum)));
					}
				}else{
					drawText(canvas, cellX, cellY, cellBX, cellBY, cellsNum/widthNum);
				}
				
			}
	}
	
	private void drawText(Canvas canvas, int cellX, int cellY, int cellBX,
			int cellBY, int cellsNum) {
		switch (cellsNum) {
		case 0:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第一节");
			break;
		case 1:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第二节");
			break;
		case 2:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第三节");
			break;
		case 3:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第四节");
			break;
		case 4:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第五节");
			break;
		case 5:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第六节");
			break;
		case 6:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第七节");
			break;
		case 7:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "第八节");
			break;
		
		default:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, ""+cellsNum);
			break;
		}
	}
	
	// 绘制单元格中的文字
	private void drawCellText(Canvas canvas, int cellX, int cellY, int cellBX,
			int cellBY, String text) {
		Paint paint = new Paint();
		paint.setFlags(Paint.ANTI_ALIAS_FLAG);
		paint.setColor(Color.BLUE);
		paint.setTextSize((cellBY - cellY) / 5 * 2);
		int textX = cellX + (cellBX - cellX) / 10;
		int textY = cellBY - (cellBY - cellY) / 3;
		canvas.drawText(text, textX, textY, paint);
	}
	// 绘制单元格中的颜色
	private void drawCellColor(Canvas canvas, int cellX, int cellY, int cellBX,
			int cellBY, int color) {
		Paint paint = new Paint();
		// 绘制备选颜色边框以及其中颜色
		paint.setColor(color);
		paint.setStyle(Paint.Style.FILL);
		canvas.drawRect(cellX + 4, cellY + 4, cellBX - 4, cellBY - 4, paint);
	}
	@Override
	public boolean onTouchEvent(MotionEvent event) {
		// TODO Auto-generated method stub
	
		float touchX = event.getX();
		float touchY = event.getY();
		
		int antion = event.getAction();
		if (antion == MotionEvent.ACTION_DOWN) {
			testTouchColorPanel(touchX, touchY);
		}
		return super.onTouchEvent(event);
	}
	
	// 检测点击事件所在的格数
	public boolean testTouchColorPanel(float x, float y) {
		if (x > firstX && y > firstY && x < firstX + firstSidesX + secondSideX * widthNum
				&& y < firstY + sideY * heightNum) {

			int ty = (int) ((y - firstY) / sideY);
			int tx;
			
			if(x - firstX - firstSidesX > 0) {
				tx = (int) ((x - firstX - firstSidesX) / secondSideX + 1);
			}else{
				tx = 0;
			}
			int index = ty * widthNum + tx;
			myFormListener.showNum(""+index);
			return true;
		}

		return false;
	}
		
}
