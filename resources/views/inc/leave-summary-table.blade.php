@if(Auth::user()->hasRole('employee')) 
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>SL</th>
                <td><strong>{{$lc->sl_count}}</strong><small>/15</small></td>
            </tr>
            <tr>
                <th>VLP</th>
                <td><strong>{{$lc->vlp_count}}</strong><small>/12</small></td>
            </tr>
            <tr>
                <th>SPL</th>
                <td><strong>{{$lc->spl_count}}</strong><small>/7</small></td>
            </tr>
            <tr>
                <th>GL</th>
                <td><strong>{{$lc->gl_count}}</strong><small>/60</small></td>
            </tr>
            <tr>
                <th>VAWCL</th>
                <td><strong>{{$lc->vawcl_count}}</strong><small>/10</small></td>
            </tr>
        </table>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <tr>
                <th>SL</th>
                <th>VLP</th>
                <th>SPL</th>
                <th>GL</th>
                <th>VAWCL</th>
            </tr>
            <tr>
                <td><strong>{{$lc->sl_count}}</strong><small>/15</small></td>
                <td><strong>{{$lc->vlp_count}}</strong><small>/12</small></td>
                <td><strong>{{$lc->spl_count}}</strong><small>/7</small></td>
                <td><strong>{{$lc->gl_count}}</strong><small>/60</small></td>
                <td><strong>{{$lc->vawcl_count}}</strong><small>/10</small></td>
            </tr>
        </table>
    </div>
@endif