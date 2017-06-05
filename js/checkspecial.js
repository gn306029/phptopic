function checkspecial(val) {
    var toalarm = false;
    var ch;
    var stralarm = new Array("<", ">", ".", "!", "'", "/", "\\");
    for (var i = 0; i < stralarm.length; i++) { //依序載入使用者輸入的每個字元
        for (var j = 0; j < val.length; j++) {
            ch = val.substr(j, 1);
            if (ch == stralarm[i]) //如果包含禁止字元
            {
                return true;
            }
        }
    }
}

function checkchinese(val){
	return val.search(RegExp("[一-"+String.fromCharCode(40869)+"]"))>-1;
}