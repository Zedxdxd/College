package rs.ac.bg.etf.pp1;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.Reader;

import java_cup.runtime.Symbol;

import org.apache.log4j.Logger;
import org.apache.log4j.xml.DOMConfigurator;

import rs.ac.bg.etf.pp1.ast.Program;
import rs.ac.bg.etf.pp1.util.Log4JUtils;
import rs.etf.pp1.mj.runtime.Code;
import rs.etf.pp1.symboltable.Tab;

public class MJParserTest {

	static {
		DOMConfigurator.configure(Log4JUtils.instance().findLoggerConfigFile());
		Log4JUtils.instance().prepareLogFile(Logger.getRootLogger());
	}
	
	public static void main(String[] args) throws Exception {
		
		Logger log = Logger.getLogger(MJParserTest.class);
		
		Reader br = null;
		try {
			if (args.length < 2) {
				System.out.println("Proslediti 2 argumenta: ime fajla sa izvornim MJ kodom i ime fajla kao rezultat prevodjenja (.obj)");
				return;
			}
			File sourceCode = new File("test/" + args[0]);
			log.info("Compiling source file: " + sourceCode.getAbsolutePath());
			
			br = new BufferedReader(new FileReader(sourceCode));
			Yylex lexer = new Yylex(br);
			
			MJParser p = new MJParser(lexer);
	        Symbol s = p.parse();  //pocetak parsiranja
	        
	        if (p.errorDetected) {
	        	log.error("Pronadjene sintaksne greske. Ne moze se nastaviti dalje.");
	        	log.error("Parsiranje neuspesno zavrseno!");
	        	return;
	        }
	        
	        Program prog = (Program)(s.value); 
	        ExtendedTab.init();
			// ispis sintaksnog stabla
	        log.info(prog.toString(""));
			log.info("===================================");
			

			// ispis prepoznatih programskih konstrukcija
			SemanticAnalyzer v = new SemanticAnalyzer();
			prog.traverseBottomUp(v);
			
			ExtendedTab.dump();
			
			if (!p.errorDetected && v.passed()) {
				File objFile = new File("test/" + args[1]);
				if (objFile.exists()) {
					objFile.delete();
				}
				
				CodeGenerator codeGenerator = new CodeGenerator();
				prog.traverseBottomUp(codeGenerator);
				Code.dataSize = SemanticAnalyzer.tvfStart;
				Code.mainPc = codeGenerator.getMainPc();
				Code.write(new FileOutputStream(objFile));
				log.info("Parsiranje uspesno zavrseno!");
			}
			else {
				log.info("Parsiranje neuspesno zavrseno!");
			}
		}
		catch (java.io.FileNotFoundException exc) {
			System.out.println("Ne postoji taj fajl!");
		}
		finally {
			if (br != null) try { br.close(); } catch (IOException e1) { log.error(e1.getMessage(), e1); }
		}
	}
	
	
}
