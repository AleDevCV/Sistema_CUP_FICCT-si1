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

<label>CI *</label>

<input
type="text"
name="ci"
value="{{ old('ci',$postulante->ci ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Nombres *</label>

<input
type="text"
name="nombres"
value="{{ old('nombres',$postulante->nombres ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Apellidos *</label>

<input
type="text"
name="apellidos"
value="{{ old('apellidos',$postulante->apellidos ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Fecha nacimiento *</label>

<input
type="date"
name="fecha_nacimiento"
value="{{ old(
'fecha_nacimiento',
isset($postulante)
? $postulante->fecha_nacimiento?->format('Y-m-d')
: ''
) }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Sexo *</label>

<select
name="sexo"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

<option
value="Masculino"
{{ old('sexo',$postulante->sexo ?? '')=='Masculino' ? 'selected':'' }}>

Masculino

</option>

<option
value="Femenino"
{{ old('sexo',$postulante->sexo ?? '')=='Femenino' ? 'selected':'' }}>

Femenino

</option>

</select>

</div>


<div>

<label>Teléfono *</label>

<input
type="text"
name="telefono"
value="{{ old('telefono',$postulante->telefono ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Email</label>

<input
type="email"
name="email"
value="{{ old('email',$postulante->email ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Ciudad *</label>

<input
type="text"
name="ciudad"
value="{{ old('ciudad',$postulante->ciudad ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Colegio *</label>

<select
name="colegio"
class="form-select"
required
style="width:100%;padding:10px;">

<option value="">

Seleccione un colegio

</option>

@foreach($colegios as $col)

<option
value="{{ $col }}"
@selected(old('colegio', $postulante->colegio ?? '') == $col)>

{{ $col }}

</option>

@endforeach

</select>

</div>


<div>

<label>Dirección *</label>

<input
type="text"
name="direccion"
value="{{ old('direccion',$postulante->direccion ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Título Bachiller *</label>

<input
type="checkbox"
name="titulo_bachiller"
value="1"
required
{{ old('titulo_bachiller', isset($postulante) && $postulante->titulo_bachiller ? 'checked' : '') }}>

Declaro poseer Título de Bachiller.

</div>


<div>

<label>Primera opción *</label>

<select
name="carrera_primera_opcion_id"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

@foreach($carreras as $carrera)

<option
value="{{ $carrera->id }}"
{{ old('carrera_primera_opcion_id',$postulante->carrera_primera_opcion_id ?? '')==$carrera->id ? 'selected':'' }}>

{{ $carrera->nombre }}

</option>

@endforeach

</select>

</div>


<div>

<label>Segunda opción</label>

<select
name="carrera_segunda_opcion_id"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

@foreach($carreras as $carrera)

<option
value="{{ $carrera->id }}"
{{ old('carrera_segunda_opcion_id',$postulante->carrera_segunda_opcion_id ?? '')==$carrera->id ? 'selected':'' }}>

{{ $carrera->nombre }}

</option>

@endforeach

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