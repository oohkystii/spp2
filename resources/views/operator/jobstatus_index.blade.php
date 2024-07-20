@extends('layouts.app_sneat')

@section('js')
    <script>
        $(document).ready(function() {
            var bar = document.querySelector(".progress-bar");

            function checkProgress() {
                @if (request('job_status_id') != '')
                    $.getJSON("{{ route('jobstatus.show', request('job_status_id')) }}",
                        function(data, textStatus, jqXHR) {
                            var progressPercent = data['progress_percentage'];
                            var progresNow = data['progress_now'];
                            var progresMax = data['progress_max'];
                            var isEnded = data['is_ended'];
                            var id = data['id'];
                            
                            bar.style.width = progressPercent + "%";
                            bar.innerText = progresNow + "%";
                            $("#progress-now" + id).text(progresNow);
                            $("#progress-max" + id).text(progresMax);

                            if (isEnded) {
                                bar.style.width = "100%";
                                bar.innerText = "100%";
                                setTimeout(function() {
                                    window.location.href = "{{ route('jobstatus.index') }}";
                                }, 1000); // delay redirect for 1 second
                            } else {
                                setTimeout(checkProgress, 1000); // check again in 1 second
                            }
                        });
                @endif
            }

            // Initial check when document is ready
            checkProgress();
        });
    </script>
@endsection


@section('content')
    <style>
        .progress-bar {
            font-size: 12px;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">{{ $title }}</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('tagihan.create') }}" class="btn btn-primary">Tagihan SPP</a>
                            <a href="{{ route('tagihanlainstep.create', ['step' => 1]) }}" class="btn btn-primary">Tagihan
                                Biaya Lain</a>
                        </div>
                        <div class="col-md-6">
                            {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                            <div class="input-group mt-6">
                                <input name="q" class="form-control" placeholder="Cari Data" aria-label="cari data"
                                    aria-describedby="button-addon2" value="{{ request('q') }}">
                                <button type="submit" class="btn btn-outline-primary" id="button-addon2">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    @if (request('job_status_id') != '')
                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: 0%;"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                            </div>
                        </div>
                    @endif
                    <div class="table-responsive mt-3 mb-3">
                        <table class="{{ config('app.table_style') }}">
                            <thead class="{{ config('app.thead_style') }}">
                                <tr>
                                    <th>No</th>
                                    <th>Job Modul</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Tanggal dibuat</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jobStatus as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->status == 'finished')
                                                {{ getClassName($item->type) }}
                                            @else
                                                <a href="{{ route('jobstatus.index', ['job_status_id' => $item->id]) }}">
                                                    {{ getClassName($item->type) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <span id="progress-now{{ $item->id }}">{{ $item->progress_now }}</span> /
                                            <span id="progress-max{{ $item->id }}">{{ $item->progress_max }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $item->status == 'finished' ? 'success' : 'info' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>{{ $item->created_at->format('d-M-Y H:i:s') }}</td>
                                        <td>{{ $item->output['message'] ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Data Tidak Ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {!! $jobStatus->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
