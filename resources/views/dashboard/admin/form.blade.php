<div class="card-body row">
    <div class="form-group  col-4">
        <label>الاسم<span
                class="text-danger">*</span></label>
        <input name="name" placeholder="ادخل الاسم"  value="{{ old('name', $data->name ?? '') }}" class="form-control  {{ $errors->has('name') ? 'border-danger' : '' }}" type="text"
               maxlength="255" />
    </div>
    <div class="form-group  col-4">
        <label>البريد الالكتروني<span
                class="text-danger">*</span></label>
        <input name="email" placeholder="ادخل البريد الالكتروني"  value="{{ old('email', $data->email ?? '') }}" class="form-control  {{ $errors->has('email') ? 'border-danger' : '' }}" type="email"
               maxlength="255" />
    </div>
    <div class="form-group  col-4">
        <label>رقم الهاتف<span
                class="text-danger">*</span></label>
        <input name="phone" placeholder="ادخل رقم الهاتف"  value="{{ old('phone', $data->phone ?? '') }}" class="form-control  {{ $errors->has('phone') ? 'border-danger' : '' }}" type="number"
               maxlength="255" />
    </div>

    <div class="form-group  col-6">
        <label>الرقم السري
                @if(request()->segment(2) == 'create') <span class="text-danger">*</span>@endif
        </label>
        <input name="password" placeholder="ادخل الرقم السري"  value="{{ old('password') }}" class="form-control  {{ $errors->has('password') ? 'border-danger' : '' }}" type="password"
               maxlength="255" />
    </div>
    <div class="form-group  col-6">
        <label>الرقم تاكيد السري
            @if(request()->segment(2) == 'create') <span class="text-danger">*</span> @endif
        </label>
        <input name="password_confirmation" placeholder="ادخل تاكيد الرقم السري"  value="{{ old('password_confirmation') }}" class="form-control  {{ $errors->has('password_confirmation') ? 'border-danger' : '' }}" type="password"
               maxlength="255" />
    </div>
    <div class="form-group col-6">
        <label>الدور</label>
        <select name="role_id"   class="form-control select2 {{ $errors->has('role_id') ? 'border-danger' : '' }}" id="kt_select2_1_modal">
            @foreach($roles as $row)
                <option
                    @if(Request::segment(1)== 'admins' && Request::segment(2)== 'edit')
                    {{ $row->id == old('role_id',  $data->role_id)  ? 'selected' : '' }}
                    @else
                    {{ $row->id == old('role_id') ? 'selected' : '' }}
                    @endif
                    value="{{ $row->id }}">{{ $row->name }}</option>
            @endforeach
        </select>
    </div>

</div>
<div class="card-footer text-left">
    <button type="Submit" id="submit" class="btn btn-success btn-default ">حفظ</button>
    <a href="{{ URL::previous() }}" class="btn btn-secondary">الغاء</a>
</div>

