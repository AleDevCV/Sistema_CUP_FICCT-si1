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

<label>Postulante *</label>

<select
name="postulante_id"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

@foreach($postulantes as $postulante)

<option
value="{{ $postulante->id }}"
{{ old('postulante_id',$examen->postulante_id ?? '')==$postulante->id ? 'selected':'' }}>

{{ $postulante->nombre_completo }}

</option>

@endforeach

</select>

</div>


<div>

<label>Materia *</label>

<select
name="materia_id"
style="width:100%;padding:10px;">

<option value="">

Seleccione

</option>

@foreach($materias as $materia)

<option
value="{{ $materia->id }}"
{{ old('materia_id',$examen->materia_id ?? '')==$materia->id ? 'selected':'' }}>

{{ $materia->nombre }}

</option>

@endforeach

</select>

</div>


<div>

<label>Número de examen *</label>

<input
type="number"
name="numero_examen"
min="1"
value="{{ old('numero_examen',$examen->numero_examen ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Nota *</label>

<input
type="number"
step="0.01"
min="0"
max="100"
name="nota"
value="{{ old('nota',$examen->nota ?? '') }}"
style="width:100%;padding:10px;">

</div>


<div>

<label>Porcentaje *</label>

<input
type="number"
step="0.01"
min="0"
max="100"
name="porcentaje"
value="{{ old('porcentaje',$examen->porcentaje ?? '') }}"
style="width:100%;padding:10px;">

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