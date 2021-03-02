function CrtCntctGrd(aSchlLst) 
{
	if(aSchlLst != '') {
		aSchlLst.forEach(function (SchlCntctVal, key) {
			key = parseInt(key+1);
			if(key > 1){
				newdiv = document.createElement('tr');
				divid = "Row_"+key;
				newdiv.setAttribute('id', divid);
				content = '';
				content += '<tr id="Row_'+key+'">';
				content += '<td><i class="fa fa-minus" onclick="DeleteCntctRow('+key+')"></i></td>';
				content += '<td><select name="nSchlType'+key+'" class="form-control"><option value="">Role</option>';
				content += '<option '+SchlCntctVal['nCntct_Role']+' == 601 ? selected : "" value="601">Admin</option>';
				content += '<option '+SchlCntctVal['nCntct_Role']+' == 602 ? selected : "" value="602">Principle</option>';
				content += '<option '+SchlCntctVal['nCntct_Role']+' == 603 ? selected : "" value="603">Assistant Principle</option>';
				content += '</select></td>';
				content += '<td><i class="fa fa-minus" onclick="DeleteCntctRow('+key+')"></i></td>';
				content += '<td><select name="nSchlType'+key+'" class="form-control"><option value="">Title</option>';
				content += '<option '+SchlCntctVal['nCntct_Title']+' == 101 ? selected : "" value="101">Dr.</option>';
				content += '<option '+SchlCntctVal['nCntct_Title']+' == 102 ? selected : "" value="102">Mr.</option>';
				content += '<option '+SchlCntctVal['nCntct_Title']+' == 103 ? selected : "" value="103">Miss.</option>';
				content += '<option '+SchlCntctVal['nCntct_Title']+' == 104 ? selected : "" value="104">Mr.</option>';
				content += '<option '+SchlCntctVal['nCntct_Title']+' == 105 ? selected : "" value="105">Ms.</option>';
				content += '<option '+SchlCntctVal['nCntct_Title']+' == 106 ? selected : "" value="106">Mrs.</option>';
				content += '</select></td>';
				content += '<td><input @error("sFrstName'+key+'") value="'+SchlCntctVal['sFrst_Name']+'" is-invalid @enderror" type="text" name="sFrstName'+key+'" required /></td>';
				content += '<td><input @error("sLstName'+key+'") value="'+SchlCntctVal['sLst_Name']+'" is-invalid @enderror" type="text" name="sLstName'+key+'" required /></td>';
				content += '<td><input @error("sPhoneNo'+key+'") value="'+SchlCntctVal['sPhone_No']+'" is-invalid @enderror" type="text" name="sPhoneNo'+key+'" required /></td>';
				content += '<td><input @error("sMobileNo'+key+'") value="'+SchlCntctVal['sMobile_No']+'" is-invalid @enderror" type="text" name="sMobileNo'+key+'" required /></td>';
				content += '<td><input @error("sEmailId'+key+'") value="'+SchlCntctVal['sEmail_Id']+'" is-invalid @enderror" type="text" name="sEmailId'+key+'" required /></td>';
				content += '</tr>';
				newdiv.innerHTML = content;
				$("#nTtlRec").val(key);
				$(".contact-table").last().append(newdiv);
			}
		});
	}
}

function CrtSpplrGrd(aSchlLst, aMlkBarLst) 
{
	if(aSchlLst != '') {
		aSchlLst.forEach(function (SchlSpplrVal, key) {
			key = parseInt(key+1);
			if(key > 1){
				newdiv = document.createElement('tr');
				divid = "Row_Supplier_"+key;
				newdiv.setAttribute('id', divid);
				content = '';
				content += '<tr id="Row_Supplier_'+key+'">';
				content += '<td><i class="fa fa-minus" onclick="DeleteSpplrRow('+key+')"></i></td>';
				content += '<td><select class="form-control @error("nMilkId'+key+'") is-invalid @enderror" name="nMilkId'+key+'"><option value="">Milk Bar</option>';
				aMlkBarLst.forEach(function (aMlkBar){
					if(SchlSpplrVal['lMilk_IdNo'] == aMlkBar["lMilk_IdNo"]){
						content += '<option selected value="'+aMlkBar["lMilk_IdNo"]+'">'+aMlkBar["sBuss_Name"]+'</option>';
					}else{
						content += '<option value="'+aMlkBar["lMilk_IdNo"]+'">'+aMlkBar["sBuss_Name"]+'</option>';
					}
				});
				content += '<td><input type="text" class="@error("lSchlDstnc'+key+'") is-invalid @enderror" name="lSchlDstnc'+key+'" value="'+SchlSpplrVal["lSchl_Dstnc"]+'" onkeypress="return IsNumber(event, this.value, \'1\')" required /></td>';
				content += '<td><input type="text" class="@error("sCntctName'+key+'") is-invalid @enderror" name="sCntctName'+key+'" value="'+SchlSpplrVal["sCntct_Name"]+'" onkeypress="return IsAlpha(event, this.value, \'15\')" required /></td>';
				content += '</tr>';
				newdiv.innerHTML = content;
				$("#nTtlRecSpplr").val(key);
				$(".supplier-table").last().append(newdiv);
			}
		});
	}
}