@if(!empty($aCartItms))
    <div class="innercontent">
        @php
            $sTtlAmo = 0;
        @endphp
        @foreach($aCartItms as $nKey => $aCrtData)
            @php
                $aGetItm = \App\Model\Item::Select('sItem_Name')->Where('lItem_IdNo',$aCrtData['lItemIdNo'])->first()->toArray();
                $sTtlAmo += $aCrtData['sItmPrc'] * $aCrtData['nItmQty'];
            @endphp
            <div class="singleproduct btn1px">
                <div class="d-flex justify-content-between ">
                    <div class="head">
                        <span>{{$aCrtData['nItmQty']}} X </span>
                        {{$aGetItm['sItem_Name']}}
                    </div>
                    <div class="singleamount d-flex">
                        <span class="pr-2">${{number_format($aCrtData['sItmPrc'] * $aCrtData['nItmQty'], 2)}}</span>
                        <div class="deletesingleamount">
                            <svg width="20" height="20" class="_1liuvga remove_item" viewBox="0 0 20 20"
                                aria-hidden="true" data-key={{$nKey}}>
                                <title>Remove</title>
                                <g fill="none" fill-rule="evenodd" stroke="#4C4C4C"
                                    stroke-width="2" transform="translate(1 1)">
                                    <circle cx="9" cy="9" r="9"></circle>
                                    <g stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M6.018 12.446l6.428-6.428M6.018 6.018l6.267 6.267">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
                @if(!empty($aCrtData['sItmVrnt']))
                    @php
                        $aVrntData = json_decode($aCrtData['sItmVrnt']);
                    @endphp
                    @foreach($aVrntData as $nVrntKey => $IVarItemIdNos)
                        @foreach($IVarItemIdNos as $IVarItemIdNo)
                            @php
                            $aVrntOpt   = \App\Model\VariantItem::Select('sItem_name')
    ->Where('IVar_Item_IdNo', $IVarItemIdNo)->first()->toArray();
                            @endphp
                            <div class="extraoptions">
                                + {{$aVrntOpt['sItem_name']}}
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </div>
        @endforeach
        <div class="singletotalline d-flex justify-content-between btn1px">
            <span>Total</span>
            <span>${{number_format($sTtlAmo, 2)}}</span>
        </div>
    </div>
    <div class="addbtncart1">
        <button class="btn addbtngrp1" id="addToCart" type="submit">
            Review Order
        </button>
    </div>
@endif