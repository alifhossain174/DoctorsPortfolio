{{-- Reuse in article create/edit forms. Pass a validated localized body array.
     Native submission is body[en][] / body[bn][] in DOM order; escape every value.
     The future request must trim/remove blank paragraphs and validate plain strings. --}}
<x-admin.localized-tabs name="body" label="Article paragraphs" :value="$body" type="paragraphs" required/>
