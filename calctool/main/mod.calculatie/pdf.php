<?php
$p = pdf_new();

/*  open new PDF file; insert a file name to create the PDF on disk */
if (pdf_begin_document($p, "", "") == 0) {
    die("Error: " . PDF_get_errmsg($p));
}

pdf_set_info($p, "Author", "Martín Gonzalez");
pdf_set_info($p, "Title", "Test PDF");
pdf_set_info($p, "Creator", "Martín Gonzalez");
pdf_set_info($p, "Subject", "Test  PDF");

pdf_begin_page_ext($p, 595, 842, "");

$font = pdf_load_font($p, "Helvetica-Bold", "winansi", "");

pdf_setfont($p, $font, 22.0);
//PDF_set_text_pos($p, 450, 780);
//PDF_show($p, "OFFERTE");
pdf_show_xy($p, "OFFERTE", 450, 780);
//PDF_continue_text($p, "(says PHP)");
PDF_end_page_ext($p, "");

PDF_end_document($p, "");

$buf = PDF_get_buffer($p);
$len = strlen($buf);

header("Content-type: application/pdf");
header("Content-Length: $len");
header("Content-Disposition: inline; filename=hello.pdf");
print $buf;

PDF_delete($p);
?>