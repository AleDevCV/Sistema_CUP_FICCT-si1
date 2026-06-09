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


<div style="display:grid;gap:20px;">

<div>

<label>

Nombre *

</label>

<input
type="text"
name="nombre"
value="{{ old('nombre',$materia->nombre ?? '') }}"
style="
width:100%;
padding:10px;">

</div>


<div>

<label>

Descripción

</label>

<textarea
name="descripcion"
style="
width:100%;
padding:10px;
height:120px;">{{ old('descripcion',$materia->descripcion ?? '') }}</textarea>

</div>


<div>

<label>

Estado *

</label>

<select
name="estado"
style="
width:100%;
padding:10px;">

<option
value="1"
{{ old('estado',$materia->estado ?? 1)==1 ? 'selected':'' }}>

Activo

</option>

<option
value="0"
{{ old('estado',$materia->estado ?? 1)==0 ? 'selected':'' }}>

Inactivo

</option>

</select>

</div>


<div>

<label>

Ponderación (%)

</label>

<input
type="number"
name="ponderacion"
min="0"
max="100"
step="0.01"
value="{{ old('ponderacion', $materia->ponderacion ?? 25.00) }}"
style="
width:100%;
padding:10px;">

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