<div class="table-responsive"><table class="table align-middle mb-0">
<thead><tr><th>Class</th><th class="text-end">Enrolled</th><th class="text-end">Completed</th><th class="text-end">Passed</th><th class="text-end">Failed</th><th class="text-end">Incomplete</th><th class="text-end">Pass % (of completed)</th></tr></thead>
<tbody>
@forelse($rows as $r)
<tr><td class="fw-semibold">{{ $r->class_name }}</td><td class="text-end">{{ $r->enrolled }}</td><td class="text-end">{{ $r->completed }}</td>
<td class="text-end text-success fw-semibold">{{ $r->passed }}</td><td class="text-end text-danger fw-semibold">{{ $r->failed }}</td>
<td class="text-end text-warning-emphasis">{{ $r->incomplete }}</td><td class="text-end">{{ $r->pass_rate === null ? 'N/A' : $r->pass_rate.'%' }}</td></tr>
@empty
<tr><td colspan="7"><div class="empty"><i class="bi bi-building"></i><p class="mb-0">No classes yet.</p></div></td></tr>
@endforelse
</tbody></table></div>
