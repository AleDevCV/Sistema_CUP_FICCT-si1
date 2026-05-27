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

<input
type="text"
name="colegio"
value="{{ old('colegio',$postulante->colegio ?? '') }}"
style="width:100%;padding:10px;">

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
type="text"
name="titulo_bachiller"
value="{{ old('titulo_bachiller',$postulante->titulo_bachiller ?? '') }}"
style="width:100%;padding:10px;">

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


<div>

<label>Grupo</label>

<select
name="grupo_id"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

@foreach($grupos as $grupo)

<option
value="{{ $grupo->id }}"
{{ old('grupo_id',$postulante->grupo_id ?? '')==$grupo->id ? 'selected':'' }}>

{{ $grupo->nombre }}

</option>

@endforeach

</select>

</div>


<div>

<label>Promedio Final</label>

<input
type="number"
step="0.01"
name="promedio_final"
value="{{ old('promedio_final',$postulante->promedio_final ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Estado Final</label>

<select
name="estado_final"
style="width:100%;padding:10px;">

<option value="">
Seleccione
</option>

<option
value="Aprobado"
{{ old('estado_final',$postulante->estado_final ?? '')=='Aprobado' ? 'selected':'' }}>

Aprobado

</option>

<option
value="Reprobado"
{{ old('estado_final',$postulante->estado_final ?? '')=='Reprobado' ? 'selected':'' }}>

Reprobado

</option>

<option
value="Pendiente"
{{ old('estado_final',$postulante->estado_final ?? '')=='Pendiente' ? 'selected':'' }}>

Pendiente

</option>

</select>

</div>


<div style="grid-column:span 2;">

<label>Otros requisitos</label>

<textarea
name="otros_requisitos"
style="width:100%;padding:10px;height:100px;">{{ old('otros_requisitos',$postulante->otros_requisitos ?? '') }}</textarea>

</div>


<div>

<label>Estado *</label>

<select
name="estado"
style="width:100%;padding:10px;">

<option
value="1"
{{ old('estado',$postulante->estado ?? 1)==1 ? 'selected':'' }}>

Activo

</option>

<option
value="0"
{{ old('estado',$postulante->estado ?? 1)==0 ? 'selected':'' }}>

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