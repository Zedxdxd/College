package Olympiad;

import java.awt.Graphics;
import java.util.LinkedHashMap;

import javax.swing.JPanel;

public abstract class Graph {
	
	protected LinkedHashMap<String, Double> data;

	public abstract void draw(Graphics g, JPanel panel);
	
	public void setData(LinkedHashMap<String, Double> map) {
		data = map;
	}
}
