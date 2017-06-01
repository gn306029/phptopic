function checkspecial(val){
	var check=false;
	for(var i = 0;i<val.length;i++){
		if((val.charCodeAt(i)<=57 && val.charCodeAt(i)>=48)||
		(val.charCodeAt(i)<=90 && val.charCodeAt(i)>=65)||
		(val.charCodeAt(i)<=122 && val.charCodeAt(i)>=97)){
		  
		}else{
			check=true;
			break;
		}
	}
	return check;
}