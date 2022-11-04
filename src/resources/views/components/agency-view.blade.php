<tr>
    <th>CUIT</th>
    <td>{{ optional($registration->{ $type })->cuit }}</td>
</tr>
<tr>
    <th>Razón Social</th>
    <td>{{ optional($registration->{ $type })->name }}</td>
</tr>
<tr>
    <th>Domicilio</th>
    <td>{{ optional($registration->{ $type })->address }}</td>
</tr>
<tr>
    <th>Teléfono</th>
    <td>{{ optional($registration->{ $type })->phone }}</td>
</tr>
<tr>
    <th>E-Mail</th>
    <td>{{ optional($registration->{ $type })->email }}</td>
</tr>
