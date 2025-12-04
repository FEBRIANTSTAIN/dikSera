<div class="dash-card p-3 mb-3">
    <h6 class="mb-2">{{ $title }}</h6>

    @if(count($data)==0)
        <div class="text-muted small">Belum ada data.</div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-bordered small">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        @foreach($cols as $c)
                            <th>{{ ucwords(str_replace('_',' ',$c)) }}</th>
                        @endforeach
                        <th>Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            @foreach($cols as $c)
                                <td>{{ $row->$c }}</td>
                            @endforeach
                            <td>
                                @if($row->dokumen_path)
                                    <a href="{{ asset('storage/'.$row->dokumen_path) }}"
                                       target="_blank">
                                       Lihat
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
