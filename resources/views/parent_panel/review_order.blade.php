@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('parent_panel.layouts.side_panel')
            <main>
                <!-- My Commissions From -->
                @include('admin_panel.layouts.message')
                
				<div class="page-breadcrumb">
					<div class="row">
						<div class="col-12">
							<h4 class="page-title">Review Your Order</h4>
						</div>
					</div>
				</div>
				<!-- My Commissions From -->
				<div class="container-fluid card-commission-section my-form my-credits-section">
					<!-- Commssions Details Tabel -->
					<div class="row pt-4">
						<div class="col-sm-12 col-lg-12 commssions-table-details table-responsive">
							<table>
								<tr>
									<th>Student Name</th>
									<th>School Name</th>
									<th>School Type</th>
									<th>Student Class</th>
									<th>Service Provider</th>
								</tr>
								<tr>
									<td>{{$aChldDtl['sFrst_Name']." ".$aChldDtl['sLst_Name']}}</td>
									<td>{{$aChldDtl['sSchl_Name']}}</td>
									<td>{{array_search($aChldDtl['lSchl_Type'], config('constant.SCHL_TYPE'))}}</td>
									<td>{{$aChldDtl['sCls_Name']}}</td>
									<td>{{$aMlkDtl['sBuss_Name']}}</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row pt-4">
						<div class="col-sm-12 col-lg-12 commssions-table-details table-responsive">
							<table class="mt-5">
								<tr>
									<th>Item ID</th>
									<th>Item Name</th>
									<th>Item Description</th>
									<th>Quantity</th>
									<th class="text-right">Price</th>
									<th class="text-right">{{array_search($aCntryDtl['nTax_Mtdh'], config('constant.TAX_MTHD'))}}</th>
									<th class="text-right">Sub Total</th>
								</tr>
								@if(!empty($aItemData))
									@php
										$total = 0;
									@endphp
                                    @foreach($aItemData As $aRec)
                                    @php
                                    	$aItmData = \App\Model\Item::Select('sItem_Name','lItem_Unq_Id','sItem_Dscrptn')->Where('lItem_IdNo',$aRec['lItemIdNo'])->first()->toArray();
                                    	$sTaxAmo = ($aRec['sItmPrc'] * $aRec['nItmQty'] * $aCntryDtl['dTax_Per']) / 100;
                                    @endphp
                                    <tr>
                                        <td>{{$aItmData['lItem_Unq_Id']}}</td>
                                        <td>{{$aItmData['sItem_Name']}}</td>
                                        <td>{{$aItmData['sItem_Dscrptn']}}</td>
                                        <td align="center" style="padding-right: 35px;">{{$aRec['nItmQty']}}</td>
                                        <td class="text-right">$ {{number_format($aRec['sItmPrc'], 2, '.', ',')}}</td>
                                        <td class="text-right">$ {{number_format($sTaxAmo, 2, '.', ',')}}</td>
                                        <td class="text-right">$ {{number_format($sTaxAmo + ($aRec['sItmPrc'] * $aRec['nItmQty']), 2, '.', ',')}}</td>
                                        
                                    </tr>
									@php
										$total += $aRec['sItmPrc'] * $aRec['nItmQty'];
									@endphp
                                    @endforeach
                                @else
                                    <tr><td colspan="6" class="text-center"><strong>No Record(s) Found</strong></td></tr>
                                @endif
							</table>
						</div>
					</div>
					<div class="row pt-4">
						<div class="col-sm-12 text-center">
							<div class="order-total mb-2">Order Total $ {{number_format($total + ($total * $aCntryDtl['dTax_Per']) / 100, 2, '.', ',')}}</div>
						</div>
					</div>
					<div class="row pt-4">
						<div class="col-lg-12 services-btns">
							<ul class="m-auto text-center pt-4 pb-4">
								<li>
									<div class="add-btn  mt-0"><button title="Change Order"  onclick="javascript:location.href='parent_panel/place_order'" class="mt-0" >Change Order</button></div>
								</li>
								<li>
									<div class="add-btn payment-btn mt-0 mtautomedia364"><button title="Proceed to Payment" class="mt-0" onClick="document.location.href='parent_panel/checkout'">Proceed to Payment</button></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')