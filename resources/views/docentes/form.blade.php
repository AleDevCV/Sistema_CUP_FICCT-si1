@if($errors->any())

<div style="
background:#fee2e2;
color:#991b1b;
padding:15px;
margin-bottom:20px;
border-radius:10px;">

<ul>

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif


<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">


<div>

<label>Usuario *</label>

<select
name="user_id"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

@foreach($users as $user)

<option
value="{{ $user->id }}"
{{ old('user_id',$docente->user_id ?? '')==$user->id ? 'selected':'' }}>

{{ $user->name }}

</option>

@endforeach

</select>

</div>


<div>

<label>CI *</label>

<input
type="text"
name="ci"
value="{{ old('ci',$docente->ci ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Nombres *</label>

<input
type="text"
name="nombres"
value="{{ old('nombres',$docente->nombres ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Apellidos *</label>

<input
type="text"
name="apellidos"
value="{{ old('apellidos',$docente->apellidos ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Teléfono *</label>

<input
type="text"
name="telefono"
value="{{ old('telefono',$docente->telefono ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Email *</label>

<input
type="email"
name="email"
value="{{ old('email',$docente->email ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Profesión *</label>

<input
type="text"
name="profesion"
value="{{ old('profesion',$docente->profesion ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Maestría</label>

<select
name="maestria"
style="width:100%;padding:10px;">

<option value="1"
{{ old('maestria',$docente->maestria ?? 0)==1 ? 'selected':'' }}>

Sí

</option>

<option value="0"
{{ old('maestria',$docente->maestria ?? 0)==0 ? 'selected':'' }}>

No

</option>

</select>

</div>


<div>

<label>Diplomado Educación Superior</label>

<select
name="diplomado_educacion_superior"
style="width:100%;padding:10px;">

<option value="1"
{{ old('diplomado_educacion_superior',$docente->diplomado_educacion_superior ?? 0)==1 ? 'selected':'' }}>

Sí

</option>

<option value="0"
{{ old('diplomado_educacion_superior',$docente->diplomado_educacion_superior ?? 0)==0 ? 'selected':'' }}>

No

</option>

</select>

</div>


<div>

<label>Contratado</label>

<select
name="contratado"
style="width:100%;padding:10px;">

<option value="1"
{{ old('contratado',$docente->contratado ?? 0)==1 ? 'selected':'' }}>

Sí

</option>

<option value="0"
{{ old('contratado',$docente->contratado ?? 0)==0 ? 'selected':'' }}>

No

</option>

</select>

</div>


<div>

<label>Estado</label>

<select
name="estado"
style="width:100%;padding:10px;">

<option value="1"
{{ old('estado',$docente->estado ?? 1)==1 ? 'selected':'' }}>

Activo

</option>

<option value="0"
{{ old('estado',$docente->estado ?? 1)==0 ? 'selected':'' }}>

Inactivo

</option>

</select>

</div>

</div>

<br>

<button
style="
background:#2563eb;
color:white;
padding:12px 20px;
border:none;
border-radius:8px;
cursor:pointer;">

Guardar

</button>