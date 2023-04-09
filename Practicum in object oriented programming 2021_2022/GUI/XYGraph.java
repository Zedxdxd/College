package Olympiad;

import java.awt.Color;
import java.awt.Graphics;

import javax.swing.JPanel;

public class XYGraph extends Graph {
	
	private int startYear, endYear;

	@Override
	public void draw(Graphics g, JPanel panel) {
		
		if (data == null) return;
		int height = panel.getHeight() - 100;
		int width = panel.getWidth() - 100;
		int x = 50;
		int y = 50;
		g.setColor(Color.white);
		g.fillRect(x, y, width, height);
		g.setColor(Color.DARK_GRAY);
		g.drawRect(x, y, width, height);
		
		double max = 0; // maximal metric
		double minYear = Double.MAX_VALUE; // highest year in data
 		double maxYear = 0; // lowest year in data
		for (String key : data.keySet()) {
			max = max < data.get(key) ? data.get(key) : max;
			maxYear = maxYear < getYear(key) ? getYear(key) : maxYear;
			minYear = minYear > getYear(key) ? getYear(key) : minYear;
		}
		
		// adjusting startYear and endYear based on minYear and maxYear so the circles
		// aren't drawn on the y axis
		if (startYear >= minYear) {
			startYear = (int)minYear - 10;
		}
		if (endYear <= maxYear) {
			endYear = (int)maxYear + 10;
		}
		
		// difference between two adjacent values on graph
		Double offsetMetric = max / 6;
		int yOffset = height / 7; // difference in pixels
		Double startValue = 0.0;
		Double endValue = offsetMetric * 7;
		
		// drawing the values on graph and lines
		for (int i = 0; i <= 7; i++) {
			Double val = offsetMetric*i;
			String show = String.format("%.2f", val);
			
			g.drawString(show, x - 7*show.length(), y + height - i * yOffset);
			g.drawLine(x, y + height - i * yOffset, 
					x - 5, y + height - i * yOffset);
		}

		// difference between two adjacent years on graph
		Double offsetYear = (endYear - startYear)*1.0/6;
		int xOffset = width / 6; // difference in pixels
		
		// drawing the years and lines on graph
		for (int i = 0; i <= 6; i++) {
			Double val = startYear + offsetYear * i;
			String show = String.format("%.0f", val);
			
			g.drawString(show, x + i * xOffset - show.length() * 3, 
					y + height + g.getFont().getSize() + 5);
			g.drawLine(x + i * xOffset, y + height, 
					x + i * xOffset, y + height + g.getFont().getSize() / 2);
			
		}
		
		// drawing points on graph
		for (String key : data.keySet()) {
			Integer curYear = getYear(key);
			int xCenter = x + (int)((width * 1.0 * (curYear - startYear) / (endYear - startYear)));
			
			Double curValue = data.get(key);
			int yCenter = y + height - (int)((height * 1.0 * (curValue - startValue) / (endValue - startValue)));
			
			if (getSeason(key).equals("Winter")) {
				g.setColor(Color.BLUE);
			}
			else {
				g.setColor(Color.red);
			}
			
			int r = width / 150;
			g.fillOval(xCenter - r, yCenter - r, 2*r, 2*r);
			
		}
	}
	
	// method for extracting the year from str, str is in format yearSeason
	private static int getYear(String str) {
		String tmp = "";
		for (int i = 0; i < str.length(); i++) {
			if (Character.isDigit(str.charAt(i))) {
				tmp += str.charAt(i);
			}
			else {
				break;
			}
		}
		return Integer.parseInt(tmp);
	}
	
	// method for extracting the season
	// if the str contains 'W', then it must be Winter
	private static String getSeason(String str) {
		if (str.contains("W")) {
			return "Winter";
		}
		else {
			return "Summer";
		}
	}

	public void setStartEndYear(int startYear, int endYear) {
		this.startYear = startYear;
		this.endYear = endYear;
	}

}
