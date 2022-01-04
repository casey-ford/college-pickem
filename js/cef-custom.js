/* GLOBAL VARIABLES */
const totalBowlGames = 43;
const deadline = new Date("December 17, 2021 11:55:00");

function toggleSelection(pick, bowlID, isDisabled){
	if (!isDisabled) {
		let oppositepick = pick.id.substring(pick.id.indexOf("|")+1) + "|" + pick.id.substring(0,pick.id.indexOf("|"));
		let bowlGame = "bowlpick_" + bowlID;
		
		if (pick.className == "unselected") {
			pick.className="selected";
			document.getElementById(oppositepick).className = "unselected";
		}			
		document.getElementById(bowlGame).value = pick.id.substring(0,pick.id.indexOf("|"));
	}
}

function buildSelectBox(myDiv, bowlID, bowlPoints, isDisabled) {
	//Create and append select list
	let selectList = document.createElement("select");
	selectList.id = "bowlpoints_" + bowlID;
	selectList.name = "bowlpoints_" + bowlID;
	selectList.className = "selectNoDuplicate";
	selectList.onchange = function () {checkForDuplicates();document.getElementById("bowlpoints_" + bowlID).blur()}
	selectList.disabled = isDisabled;
	myDiv.appendChild(selectList);
		
	//Create and append the options
	for (let i = 1; i <= totalBowlGames; i++) {
		let option = document.createElement("option");
		option.value = i;
		option.text = i;
		if (i == bowlPoints) {
			option.selected = true;
		}
		selectList.appendChild(option);
	}
}

function checkForDuplicates () {
	let selectBoxes = document.getElementsByTagName('select');
	let pointValues = [];
	let dupesCount = 0;
	
	for (let j = 0; j < selectBoxes.length; j++) {
		pointValues.push(selectBoxes[j].value);
	}

	for (let i = 0; i < selectBoxes.length; i++) {
		if (pointValues.indexOf(selectBoxes[i].value) != pointValues.lastIndexOf(selectBoxes[i].value)) {
			selectBoxes[i].className = "selectDuplicate";
			document.getElementById("bowl_" + (i + 1)).className = "divTableCellDuplicate";
			dupesCount++;
		}
		else {
			selectBoxes[i].className = "selectNoDuplicate";
			document.getElementById("bowl_" + (i + 1)).className = "divTableCell";
		}
	}

	if (dupesCount == 0) {
		let tooltips = document.getElementsByClassName("tooltiptext")
		for (let i = 0; i < tooltips.length; i++) {
			tooltips[i].innerHTML = "";
		}	
		return true;
	}
	else {
		findMissingValues (pointValues.sort(function(a, b){return a-b}), selectBoxes.length);
		return false;
	}
}


function findMissingValues(arr, N) {
/* Function to find the missing elements. Initialize an array with zero of size equals to the maximum element in the array */
	let pointValues = new Uint8Array(arr[N - 1] + 1);
	let missingValues = new Array ();

	// Make pointValues[i]=1 if i is present in the array
	for (let i = 0; i < N; i++) {
		// If the element is present make pointValues[arr[i]]=1
		pointValues[arr[i]] = 1;
	}
    
	// Print the indices where pointValues[i]=0
	for (let i = 1; i <= totalBowlGames; i++) {
		if (pointValues[i] == 0) {
			//document.write( i + " ");
			missingValues.push(i);
		}
	}
	missingValues.sort(function(a, b){return a - b});
	
	let warningMessage = "You are missing the following values: " + missingValues.toString();
	let tooltips = document.getElementsByClassName("tooltiptext")
	for (let i = 0; i < tooltips.length; i++) {
		tooltips[i].innerHTML = warningMessage;
	}	
}

function randomizePicks() {
	let min = 0;
	let max = 1;
	let bowlCount = totalBowlGames;
	let pointsArray = [];
			
	for (let i = 1; i <= bowlCount; i++) {
		let pick = i + (Math.floor(Math.random() * (max - min + 1) + min) * bowlCount);
		let oppositePick;
		if (pick <= bowlCount) {
			oppositePick = pick + bowlCount;
		}
		else {
			oppositePick = pick - bowlCount;				
		}
		let = imgID = pick + "|" + oppositePick;
		toggleSelection(document.getElementById(imgID),i);
	}
	checkForDuplicates();
}

function randomizeConf() {
	let bowlCount = totalBowlGames;
	let valueAdded = false;
	let pointsArray = [];
		
	for (let i = 1; i <= bowlCount; i++) {
		do {
			valueAdded = false;
			let n = Math.floor(Math.random() * bowlCount + 1);
			if (pointsArray.indexOf(n) == -1) {
				pointsArray[i] = n;
				document.getElementById("bowlpoints_" + i).value = pointsArray[i];
				valueAdded = true;
			}
		}
		while (valueAdded != true);			
	}
	checkForDuplicates();
}

function validateForm() {
	let bowlPick;
	let bowlCount = totalBowlGames;
	let d = new Date();

	if (d < deadline) {
		if (checkForDuplicates()) {
			for (let i = 1; i <= bowlCount; i++) {
				if (i != bowlCount) {
					bowlPick = document.forms["makePicks"]["bowlpick_" + i].value;
					if (bowlPick == null || bowlPick == "" || bowlPick == "0") {
						frm = document.getElementById('submitForm');
						frm.action = './makepicks.php#oopsModal'
						i = bowlCount + 1;
					}
				}
			}
		}
		else {
			alert ("Duplicate Point Values!\n\nLook Over Your Picks and Re-Submit");
			return false;
		}
	}
	else {
		alert (deadline);
		alert (d);
		frm = document.getElementById('submitForm');
		frm.action = './makepicks.php#deadlineModal'
	}
}