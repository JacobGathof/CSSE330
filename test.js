var maxTableX = 0; var maxTableY = 0;
var minTableX = 0; var minTableY = 0;

var characterName;
var might_index; var might_values;
var speed_index; var speed_values;
var knowledge_index; var knowledge_values;
var sanity_index; var sanity_values;

var currentSum = 0;
var currentTile;



function update(){
	
	var stats = document.getElementById("PlayerStats");
	var children = stats.childNodes;
	
	characterName=children[0].id;
	sanity_index=children[1].id;
	sanity_values=children[2].id;
	speed_index=children[3].id;
	speed_values=children[4].id;
	might_index=children[5].id;
	might_values=children[6].id;
	knowledge_index=children[7].id;
	knowledge_values=children[8].id;
	
	
	var doc = document.getElementById("StatsTable");
	
	var ii = document.getElementById("stats_index").cells;
	ii[0].innerHTML=characterName;
	ii[1].innerHTML=sanity_index;
	ii[2].innerHTML=speed_index;
	ii[3].innerHTML=might_index;
	ii[4].innerHTML=knowledge_index;
	
	var jj = document.getElementById("stats_values").cells;
	jj[0].innerHTML="Values";
	jj[1].innerHTML=sanity_values;
	jj[2].innerHTML=speed_values;
	jj[3].innerHTML=might_values;
	jj[4].innerHTML=knowledge_values;
	
	var kk = document.getElementById("stats_no_dice").cells;
	kk[0].innerHTML="# of Dice";
	kk[1].innerHTML=sanity_values[sanity_index];
	kk[2].innerHTML=speed_values[speed_index];
	kk[3].innerHTML=might_values[might_index];
	kk[4].innerHTML=knowledge_values[knowledge_index];
} 

function addStat(i, st){
	switch(st){
		case 'might' : might_index=parseInt(i)+parseInt(might_index); break;
		case 'sanity' : sanity_index=parseInt(i)+parseInt(sanity_index); break;
		case 'speed' : speed_index=parseInt(i)+parseInt(speed_index); break;
		case 'knowledge' : knowledge_index=parseInt(i)+parseInt(knowledge_index); break;
	}

	var ii = document.getElementById("stats_index").cells;
	ii[1].innerHTML=sanity_index;
	ii[2].innerHTML=speed_index;
	ii[3].innerHTML=might_index;
	ii[4].innerHTML=knowledge_index;
	
	var kk = document.getElementById("stats_no_dice").cells;
	kk[1].innerHTML=sanity_values[sanity_index];
	kk[2].innerHTML=speed_values[speed_index];
	kk[3].innerHTML=might_values[might_index];
	kk[4].innerHTML=knowledge_values[knowledge_index];
}

function rollSanity(){
	
	currentSum = 0;
	var cells = document.getElementById("Dice").rows[0].cells;
	var num = sanity_values[sanity_index];
	for(var i = 0; i < num; i++){
		var roll = Math.floor(Math.random()*3)
		cells[i].innerHTML = roll;
		currentSum += roll;
	}
	for(var i = num; i < 8; i++){
		cells[i].innerHTML = " ";
	}
	cells[8].innerHTML = "Sum : "+currentSum;
}

function rollSpeed(){
	
	currentSum = 0;
	var cells = document.getElementById("Dice").rows[0].cells;
	var num = speed_values[speed_index];
	for(var i = 0; i < num; i++){
		var roll = Math.floor(Math.random()*3)
		cells[i].innerHTML = roll;
		currentSum += roll;
	}
	for(var i = num; i < 8; i++){
		cells[i].innerHTML = " ";
	}
	cells[8].innerHTML = "Sum : "+currentSum;
}

function rollMight(){
	
	currentSum = 0;
	var cells = document.getElementById("Dice").rows[0].cells;
	var num = might_values[might_index];
	for(var i = 0; i < num; i++){
		var roll = Math.floor(Math.random()*3)
		cells[i].innerHTML = roll;
		currentSum += roll;
	}
	for(var i = num; i < 8; i++){
		cells[i].innerHTML = " ";
	}
	cells[8].innerHTML = "Sum : "+currentSum;
}

function rollKnowledge(){
	
	currentSum = 0;
	var cells = document.getElementById("Dice").rows[0].cells;
	var num = knowledge_values[knowledge_index];
	for(var i = 0; i < num; i++){
		var roll = Math.floor(Math.random()*3)
		cells[i].innerHTML = roll;
		currentSum += roll;
	}
	for(var i = num; i < 8; i++){
		cells[i].innerHTML = " ";
	}
	cells[8].innerHTML = "Sum : "+currentSum;
}

function expandTable(id){
	
	size = id.split(",");
	
	while(size[0] <= minTableX){
		minTableX--;
		var table = document.getElementById("BoardTable");
		var rows = table.children;
		for(var i = 0; i < rows.length; i++){
			
			var row = rows[i];
			var cell = document.createElement("td");
			foo(cell, minTableX, minTableY+i);
			
			var img = document.createElement("img");
			img.id=""+minTableX+","+(minTableY+i);
			img.height = 100;
			img.width = 100;
			
			cell.appendChild(img);
			row.insertBefore(cell, row.firstChild);
		}
	}
	
	while(size[0] >= maxTableX){
		maxTableX++;
		
		var table = document.getElementById("BoardTable");
		
		var rows = table.children;
		
		for(var i = 0; i < rows.length; i++){
			
			var row = rows[i];
			var cell = document.createElement("td");
			foo(cell, maxTableX, minTableY+i);
			
			var img = document.createElement("img");
			img.id=""+maxTableX+","+(minTableY+i);
			img.height = 100;
			img.width = 100;
			
			
			cell.appendChild(img);
			row.appendChild(cell);
		}
	}
	
	while(size[1] <= minTableY){ // should grow the table at the bottom
		minTableY--;
		
		var doc = document.getElementById("BoardTable");
		
		var row = document.createElement("tr");
		
		for(var i = minTableX; i <= maxTableX; i++){
			
			var cell = document.createElement("td");
			
			foo(cell, i, minTableY);
			
			
			var img = document.createElement("img");
			img.id=""+i+","+minTableY;
			img.height = 100;
			img.width = 100;
			
			cell.appendChild(img);
			row.appendChild(cell);
		}
		
		doc.insertBefore(row, doc.firstChild);	
		
	}
	
	while(size[1] >= maxTableY){ // should grow the table at the top
		maxTableY++;
		
		var doc = document.getElementById("BoardTable");
		
		var row = document.createElement("tr");
		
		for(var i = minTableX; i <= maxTableX; i++){
			
			var cell = document.createElement("td");
			
			foo(cell, i, maxTableY);
			
			
			var img = document.createElement("img");
			img.id=""+i+","+maxTableY;
			img.height = 100;
			img.width = 100;
			
			cell.appendChild(img);
			row.appendChild(cell);
		}
		
		doc.appendChild(row);
		
	}	
}

function foo(cell, i, j){
	cell.onclick = function(){placeTile(""+i+","+j)};
}

function placeTile(id){
	
	if (document.getElementById(id).src == '') {
		console.log(id);
		expandTable(id);
		
		var ele = document.getElementById(id);
		
		ele.src = document.getElementById('nextRoom').src;
		ele.className = document.getElementById('nextRoom').className;
	}
}

function buildTableFromDatabase(positions, rotations, imagePaths) {
	
	console.log(positions);
	console.log(imagePaths);
	
	var doc = document.getElementById("BoardTable");
	var row = document.createElement("tr");
	var cell = document.createElement("td");
			
	foo(cell, 0, 0);
	
	var img = document.createElement("img");
	img.id=""+0+","+0;
	img.height = 100;
	img.width = 100;
	var angle = ['0', '90', '180', '270'];
	
	cell.appendChild(img);
	row.appendChild(cell);
	doc.appendChild(row);
	
	for(var i = 0; i < positions.length; i++){
		expandTable(positions[i]);
		document.getElementById(positions[i]).src = 'Rooms/' + imagePaths[i];
		document.getElementById(positions[i]).className = 'rotateimg' + angle[rotations[i]];
	}
	
	
}