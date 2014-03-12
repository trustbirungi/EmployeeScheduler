/*********************************************************
	File: ColorSelector.js
	Project: Employee Scheduler
	Author: John Finlay
	Revision: $Revision: 1.2 $
	Date: $Date: 2004/06/02 21:38:14 $
	Comments:
		Javascript to print a color selector popup div
		
	Copyright (C) 2003  Brigham Young University

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
**********************************************************/

//-- global variable to hold a counter of how many selectors are printed on a page
var selectorCount = 0;
var selectorRef = null;

function ColorSelector(field, auto) {
	//-- the field on the page to update the color
	this.field = field;
	if (field.value.indexOf("#")!=-1) this.color = field.value.substr(1);
	else this.color = field.value;
	this.auto = auto;
	selectorCount++;
	this.id = selectorCount;
	
	//-- function to store the color back into the main form field
	this.selectColor = function() {
		this.setCompositeColor();
		this.field.value = ncolor;
		this.field.style.backgroundColor = ncolor;
		this.hide();
	}
	
	//-- function to change a color value
	this.setColor = function(inc, ncolor) {
		val = parseInt(inc.value);
		if ((isNaN(val)) || (val > 255)) {
			val = 255;
			inc.value=val;
		}
		if (val < 0) {
			val = 0;
			inc.value=val;
		}
		hex = val.toString(16);
		if (hex.length<2) hex = "0"+hex;
		ncolor = ncolor.replace(/##/, hex);
		inc.style.backgroundColor = ncolor;
		this.setCompositeColor();
	}

	//-- function to set the composite color
	this.setCompositeColor = function() {
		rf = document.getElementById("red"+this.id);
		gf = document.getElementById("green"+this.id);
		bf = document.getElementById("blue"+this.id);
		
		rval = parseInt(rf.value);
		gval = parseInt(gf.value);
		bval = parseInt(bf.value);
		
		ncolor = "";
		rhex = rval.toString(16);
		if (rhex.length<2) rhex = "0"+rhex;
		ghex = gval.toString(16);
		if (ghex.length<2) ghex = "0"+ghex;
		bhex = bval.toString(16);
		if (bhex.length<2) bhex = "0"+bhex;
		ncolor = ncolor + rhex;
		ncolor = ncolor + ghex;
		ncolor = ncolor + bhex;
		ncolor = "#"+ncolor;
		
		comp = document.getElementById("composite"+this.id);
		comp.style.backgroundColor = ncolor;
		comp.style.borderColor = ncolor;
		comp.innerHTML = ncolor;
		
		if ((this.auto)&&(this.field)) {
			this.field.value = ncolor;
			this.field.style.backgroundColor = ncolor;
		}
	}
	
	this.gradientSelect = function(col, num) {
		rf = document.getElementById(col+this.id);
		rf.value = num;
		colstr = "##0000";
		if (col=="green") colstr = "00##00";
		if (col=="blue") colstr = "0000##";
		this.setColor(rf, colstr);
	}

	this.writeSelector = function() {
		document.writeln('<div id="colorselector'+this.id+'" style="display: none; position: absolute; border: outset #FFFFFF 3px; background-color: black; color: white; padding-top: 3px; font-weight: bold; text-align: center;">');
		document.writeln('<div id="composite'+this.id+'" style="width: 205px; height: 30px; text-align: center; background-color: '+this.color+'; font-family: arial; font-size: 16pt; margin: 2px;">');
		document.writeln(this.color);
		document.writeln('</div>');
		document.writeln('<table style="color: white;"><tr>');
		document.writeln('<td>R:<input type="text" size="3" maxlength="3" name="red'+this.id+'" id="red'+this.id+'" value="'+parseInt(this.color.substr(0,2), 16)+'" onchange="selectorRef.setColor(this, \'##0000\');" style="background-color: #'+this.color.substr(0,2)+'0000; border: solid white 1px; color: white; font-weight: bold;" /></td>');
		document.writeln('<td>G:<input type="text" size="3" maxlength="3" name="green'+this.id+'" id="green'+this.id+'" value="'+parseInt(this.color.substr(2,2), 16)+'" onchange="selectorRef.setColor(this, \'00##00\');" style="background-color: #00'+this.color.substr(2,2)+'00; border: solid white 1px; color: white; font-weight: bold;" /></td>'); 
		document.writeln('<td>B:<input type="text" size="3" maxlength="3" name="blue'+this.id+'" id="blue'+this.id+'" value="'+parseInt(this.color.substr(4,2), 16)+'" onchange="selectorRef.setColor(this, \'0000##\');" style="background-color: #0000'+this.color.substr(4,2)+'; border: solid white 1px; color: white; font-weight: bold;" /></td>');
		document.writeln('</tr><tr>');
		document.writeln('<td><div id="redimg'+this.id+'" style="position: relative; border: solid white 1px;" onMouseDown="selectorRef.gradientSelect(\'red\', (event.y-document.getElementById(\'redimg'+this.id+'\').offsetTop+64)*2);"><img src="images/red.png" style="width: 100%; height: 128px" onMouseDown="return false;" /></div></td>');
		document.writeln('<td><div id="greenimg'+this.id+'" style="position: relative; border: solid white 1px;" onMouseDown="selectorRef.gradientSelect(\'green\', (event.y-document.getElementById(\'greenimg'+this.id+'\').offsetTop+64)*2);"><img src="images/green.png" style="width: 100%; height: 128px" /></div></td>');
		document.writeln('<td><div id="blueimg'+this.id+'" style="position: relative; border: solid white 1px;" onMouseDown="selectorRef.gradientSelect(\'blue\', (event.y-document.getElementById(\'blueimg'+this.id+'\').offsetTop+64)*2);"><img src="images/blue.png" style="width: 100%; height: 128px" /></div></td>');
		document.writeln('</tr><tr>');
		document.writeln('<td colspan="3"><input type="button" value="Choose" onclick="selectorRef.selectColor();" /><input type="button" value="Cancel" onclick="selectorRef.hide();" /></td>');
		document.writeln('</tr></table></div>');
	}
	
	this.show = function() {
		//-- only allow one selector to be open at a time
		if (selectorRef!=null) selectorRef.hide();
		selectorRef = this;
		
		div = document.getElementById('colorselector'+this.id);
		div.style.display='block';
	}
	
	this.hide = function() {
		div = document.getElementById('colorselector'+this.id);
		div.style.display='none';
		selectorRef = null;
	}
}