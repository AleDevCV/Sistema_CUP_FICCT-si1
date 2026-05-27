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

<label>Nombre *</label>

<input
type="text"
name="nombre"
value="{{ old('nombre',$grupo->nombre ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Código *</label>

<input
type="text"
name="codigo"
value="{{ old('codigo',$grupo->codigo ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Aula *</label>

<input
type="text"
name="aula"
value="{{ old('aula',$grupo->aula ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Horario *</label>

<input
type="text"
name="horario"
placeholder="Ej: Lun-Mie-Vie 08:00-10:00"
value="{{ old('horario',$grupo->horario ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Capacidad máxima *</label>

<input
type="number"
min="1"
name="capacidad_maxima"
value="{{ old('capacidad_maxima',$grupo->capacidad_maxima ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Estado *</label>

<select
name="estado"
style="width:100%;padding:10px;">

<option
value="1"
{{ old('estado',$grupo->estado ?? 1)==1 ? 'selected':'' }}>

Activo

</option>

<option
value="0"
{{ old('estado',$grupo->estado ?? 1)==0 ? 'selected':'' }}>

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