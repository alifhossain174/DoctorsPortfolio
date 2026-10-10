<x-admin.localized-tabs name="title" label="Library title" :value="$media?->title ?? []" required hint="A descriptive name for this image in the library."/>
<x-admin.localized-tabs name="alt_text" label="Default alternative text" :value="$media?->alt_text ?? []" hint="Describe the visible image. A content screen may provide more specific alternative text."/>
<x-admin.localized-tabs name="caption" label="Default caption" type="textarea" :value="$media?->caption ?? []"/>
<x-admin.localized-tabs name="provenance_note" label="Source / provenance" type="textarea" :value="$media?->provenance_note ?? []" hint="Record the actual source and permission or credit details; do not invent them."/>
<x-admin.localized-tabs name="stock_note" label="Stock-photo / authenticity note" type="textarea" :value="$media?->stock_note ?? []" hint="Keep relevant stock-image caveats. Uploading a file does not verify its identity or rights."/>
