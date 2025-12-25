from pathlib import Path
path = Path('app/Http/Controllers/ExportController.php')
text = path.read_text(encoding='latin-1')
marker = "    public function index(Request $request)"
start = text.index(marker)
brace_start = text.index('{', start)
count = 0
end = None
for idx in range(brace_start, len(text)):
    ch = text[idx]
    if ch == '{':
        count += 1
    elif ch == '}':
        count -= 1
        if count == 0:
            end = idx + 1
            break
if end is None:
    raise SystemExit('end not found')
new_method = """    public function index(Request $request)
    {
        if (!$request->user()->can('rapports.export')) {
            abort(403, 'Vous n\\'avez pas les permissions nécessaires pour exporter des rapports.');
        }

        $filters = [
            'statut' => $request->statut,
            'type_demande' => $request->type_demande,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
        ];

        $previewQuery = Demande::with('demandeur')->latest();
        if ($request->filled('statut')) {
            $previewQuery->where('statut', $request->statut);
        }
        if ($request->filled('type_demande')) {
            $previewQuery->where('type_demande', $request->type_demande);
        }
        if ($request->filled('date_start') and $request->filled('date_end')) {
            $previewQuery->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        $previewDemandes = $previewQuery->take(10)->get();

        return view('exports.index', [
            'statusOptions' => self::STATUS_OPTIONS,
            'typeOptions' => self::TYPE_OPTIONS,
            'filters' => $filters,
            'previewDemandes' => $previewDemandes,
        ]);
    }
"""
new_text = text[:start] + new_method + text[end:]
path.write_text(new_text, encoding='latin-1')
