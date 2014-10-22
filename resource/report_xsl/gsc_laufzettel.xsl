<?xml version="1.0" encoding="UTF-8"?><xsl:stylesheet version="1.0" xmlns:j4lif="http://java4less.com/fop/iform" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:j4lext="xalan://com.java4less.xreport.fop.XLSTExtension" extension-element-prefixes="j4lext j4luserext" xmlns:j4luserext="xalan://com.java4less.xreport.fop.XLSTDummyExtension"  >
<xsl:template match="/"><fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
<fo:layout-master-set>
<fo:simple-page-master master-name="master0" page-width="21.0cm" page-height="29.7cm" margin-top="1.0cm"   margin-bottom="1.0cm" margin-left="2.0cm"  margin-right="2.0cm"  > 
  <fo:region-body  region-name="body0" margin-top="0.0cm" margin-bottom="0.51cm"  /> 
  <fo:region-before region-name="header0" extent="0.0cm"/> 
  <fo:region-after region-name="footer0" extent="0.51cm"/> 
  </fo:simple-page-master>
</fo:layout-master-set>
<fo:page-sequence master-reference="master0">
<fo:static-content flow-name="header0">
<!-- d073319e93554796aee4c48fdaefaff0 -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="17.0cm"/>
  <fo:table-body>
    <fo:table-row   background-color="#ffffff"  height="0.0cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="17.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.0cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
  </fo:table-body>
</fo:table>
</fo:static-content>
<fo:static-content flow-name="footer0">
<!-- 63f3a612b35341289590db682af8db2d -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="17.0cm"/>
  <fo:table-body>
    <fo:table-row   background-color="#ffffff"  height="0.51cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="0.5cm"/>
      <fo:table-column column-width="11.0cm"/>
      <fo:table-column column-width="5.5cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.51cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">

<!-- e8652cf2bd364db991627864c75b188a -->
<fo:block xmlns:date="http://exslt.org/dates-and-times" font-size="8pt"  font-family="SansSerif"  color="#000000"   text-align="left">
     <xsl:variable name="now" select="date:date-time()"/>
     <xsl:text>GSC Laufzettel </xsl:text>
     <xsl:value-of select="date:day-in-month($now)"/>
     <xsl:text>.</xsl:text>
     <xsl:value-of select="date:month-in-year($now)"/>
     <xsl:text>.</xsl:text>
     <xsl:value-of select="date:year($now)"/>
     <xsl:text> </xsl:text>
     <xsl:value-of select="date:hour-in-day($now)"/>
     <xsl:text>:</xsl:text>
     <xsl:value-of select="date:minute-in-hour($now)"/>
     <xsl:text>:</xsl:text>
     <xsl:value-of select="date:second-in-minute($now)"/>
  </fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 1ccdb0b0e1e3417c8d8415b80d2054c4 -->
      <fo:block     margin-left="0.0cm" margin-right="0.5cm"  margin-top="0.0cm"  font-size="8pt"  font-family="SansSerif"  color="#000000"   text-align="right">Seite <fo:page-number></fo:page-number> von <fo:page-number-citation ref-id='last-page'/></fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
  </fo:table-body>
</fo:table>
</fo:static-content>
<fo:flow flow-name="body0"> 

<!-- START Area Header -->
<!-- 11b30adb221a4154b18e69cc2afd077b -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="0.0cm"/>
  <fo:table-column column-width="1.25cm"/>
  <fo:table-column column-width="1.75cm"/>
  <fo:table-column column-width="5.0cm"/>
  <fo:table-column column-width="1.0cm"/>
  <fo:table-column column-width="8.0cm"/>
  <fo:table-body>
    <fo:table-row    background-color="#ffffff"  height="0.51cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="3">
      <!-- 33cdf657bf554537b48b03d9c321fffc -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/post_anrede"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-rows-spanned="7"  number-columns-spanned="1">
      <!-- 4e24104d5b0a4a798a679f04c01745df -->
      <fo:block   text-align="right"    margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm" ><fo:external-graphic content-width="scale-to-fit" content-height="scale-to-fit" width="8.0cm"  height="4.0cm"  position="absolute"  ><xsl:attribute name="src"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/partner_logo"></xsl:value-of></xsl:attribute></fo:external-graphic>
</fo:block>
        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.46cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="3">
      <!-- 993f725debf949868875f6e2bbfb46ed -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/post_name1"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.49cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="3">
      <!-- e0abd0875eb94b25931e2fe78662f9dd -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.03cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/post_name2"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.49cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="3">
      <!-- 262c53aa502c4a929f0f57ecafddb8a3 -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.04cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/post_strasse"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.51cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- ab8c546999844d839d17f6230ccbd003 -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.05cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="format-number(/data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/post_plz, '00000')"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="2">
      <!-- b254affb6c494394900e16ed294db299 -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.05cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/post_ort"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.57cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.97cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="2">
      <!-- 663b93b5077c4dc28c3ae7c0e558008a -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Kundennummer</fo:block>
      <!-- aff285bafaeb49a49181729796ac522d -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.01cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Bestellung vom</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 9aad98c770594b528b7a2db56dc4fbdc -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/partner_nr"></xsl:value-of></fo:block>
      <!-- 92a049ace3f04d10a754782c525e445b -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.01cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/import_stack_title"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="0.5cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    <fo:table-row    background-color="#ffffff"  height="-0.5cm"   >
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
<fo:table-row>
<fo:table-cell number-columns-spanned="6">
<fo:block>

<!-- START Area Detail 9 -->
<!-- 6466432a551945ab8677188a34895a49 -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="8.0cm"/>
  <fo:table-column column-width="1.0cm"/>
  <fo:table-column column-width="8.0cm"/>
  <fo:table-body>
    <fo:table-row    background-color="#ffffff"  height="1.5cm"   >
        <fo:table-cell    number-columns-spanned="1">
      <fo:block   margin-left="0.0cm"  margin-top="0.0cm" ><fo:instream-foreign-object  content-width="8.0cm" content-height="1.5cm"  >
<!-- a5796a55b286467bad0831c453894fdd -->
<j4lbarcode xmlns="http://java4less.com/j4lbarcode/fop" mode="inline"><Barcode1D><VALUE><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/url_banderole"></xsl:value-of></VALUE><X>1</X><BARHEIGHT>30</BARHEIGHT><LEFTMARGIN>10</LEFTMARGIN><SET>A</SET><PROCESSTILDE>false</PROCESSTILDE><TYPE>CODE128</TYPE><N>2</N><TOPMARGIN>25</TOPMARGIN><CHECKSUM>true</CHECKSUM></Barcode1D></j4lbarcode></fo:instream-foreign-object></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <fo:block   margin-left="0.0cm"  margin-top="0.0cm" ><fo:instream-foreign-object  content-width="8.0cm" content-height="1.5cm"  >
<!-- a5796a55b286467bad0831c453894fdd -->
<j4lbarcode xmlns="http://java4less.com/j4lbarcode/fop" mode="inline"><Barcode1D><VALUE><xsl:value-of select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external/ListItemOfReportDetailorder_no_external/url_versand"></xsl:value-of></VALUE><X>1</X><BARHEIGHT>30</BARHEIGHT><LEFTMARGIN>10</LEFTMARGIN><SET>A</SET><PROCESSTILDE>true</PROCESSTILDE><TYPE>CODE128</TYPE><N>2</N><TOPMARGIN>25</TOPMARGIN><CHECKSUM>true</CHECKSUM></Barcode1D></j4lbarcode></fo:instream-foreign-object></fo:block>
        </fo:table-cell>
    </fo:table-row>
  </fo:table-body>
</fo:table>

<!-- END Area Detail 9 -->
</fo:block>
</fo:table-cell>
</fo:table-row>
<fo:table-row>
<fo:table-cell number-columns-spanned="6">
<fo:block>

<!-- START Area Detail -->
<xsl:if test="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external" >
<!-- 5c4937a970024a0694ebfb7b95a0fd19 -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="17.0cm"/>
  <fo:table-body>
  <xsl:for-each select="data/ReportDetail/ListOfReportDetail/ListOfReportDetailorder_no_external">
  <xsl:sort select="ListItemOfReportDetailorder_no_external/lfdnr"  data-type="number"  order="ascending"  />
    <fo:table-row   background-color="#ffffff"  height="0.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="17.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.5cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
    <fo:table-row   background-color="#ffffff"  height="0.6cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="4.0cm"/>
      <fo:table-column column-width="13.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.6cm">
        <fo:table-cell    number-columns-spanned="1">
      <!-- f0938bf2e5824bbfa31def33e7003a3e -->
      <fo:block     margin-left="0.0cm" margin-right="1.84cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Bd"  font-weight="bold"  color="#000000"   text-align="left">Auftrags-Nr.</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- f8369a87f0c541599191d924cabf78e5 -->
      <fo:block     margin-left="0.0cm" margin-right="11.65cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Bd"  font-weight="bold"  color="#000000"   text-align="left"><xsl:value-of select="ItemOfReportDetailorder_no_external/order_no_external"></xsl:value-of></fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
<fo:table-row>
<fo:table-cell number-columns-spanned="1">
<fo:block>

<!-- START Area Detail 3 -->
<xsl:if test="ItemOfReportDetailorder_no_external" >
<!-- c77b047b26b74e4984cde0c99f1736db -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="17.0cm"/>
  <!-- 0a0094d07b814e2c9546a8d8480e3861 -->
  <fo:table-header>
    <fo:table-row   background-color="#ffffff"  height="0.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="2.0cm"/>
      <fo:table-column column-width="2.0cm"/>
      <fo:table-column column-width="11.0cm"/>
      <fo:table-column column-width="2.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.5cm">
        <fo:table-cell    number-columns-spanned="1">
      <!-- e584be1db4004909863ad7e1611fa479 -->
      <fo:block     margin-left="0.0cm" margin-right="0.5cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Position</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- b8e08f48e7b646e09353c3893883149b -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Artikel-Nr.</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 548be73631134ed69781fe95dfe30ef7 -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Bezeichnung</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 134be212f65f40fa8ea3cc6a25dab109 -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Menge</fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
    <fo:table-row   background-color="#ffffff"  height="0.25cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="17.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.25cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block text-align-last="justify">      <!-- f54324989dbd49a99b55e4d20b10adba -->
      <fo:leader leader-pattern="rule"   color="#000000"  rule-thickness="1.0pt"  rule-style="solid" />        </fo:block>        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
  </fo:table-header>
  <fo:table-body>
  <xsl:for-each select="ItemOfReportDetailorder_no_external">
    <fo:table-row   background-color="#ffffff"  height="0.3cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="2.0cm"/>
      <fo:table-column column-width="2.0cm"/>
      <fo:table-column column-width="6.0cm"/>
      <fo:table-column column-width="5.0cm"/>
      <fo:table-column column-width="2.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.3cm">
        <fo:table-cell    number-columns-spanned="1">
      <!-- c9cf74d99fcc4bc1903cd14dc58d816c -->
      <fo:block     margin-left="0.0cm" margin-right="0.5cm"  margin-top="0.0cm"  font-size="8pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="right"><xsl:value-of select="lfdnr"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- eec0febca9674ecf977b6eaa4a9c6b62 -->
      <fo:block     margin-left="0.0cm" margin-right="0.5cm"  margin-top="0.0cm"  font-size="8pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="right"><xsl:value-of select="product_item_no"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 0ecb0f9633c94a05978896986bd9b39b -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="8pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="product_title"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- c2d007878e3c4b6cba86ebb913ca4f3c -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="8pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="product_item_title"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 96c0f1e446894279bae2ee5bbf4f255c -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="8pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="amount"></xsl:value-of></fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
    <fo:table-row   background-color="#ffffff"  height="0.29cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="4.0cm"/>
      <fo:table-column column-width="13.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.29cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 486cfec43f6b4f548cd9ba8df3d95caf -->
      <fo:block     margin-left="0.0cm" margin-right="2.0cm"  margin-top="0.0cm"  font-size="8pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="banderole"></xsl:value-of></fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
<fo:table-row>
<fo:table-cell number-columns-spanned="1">
<fo:block>

<!-- START Area Detail 8 -->
<xsl:if test="ListOfReportDetailorder_item_authkey/ItemOfReportDetailorder_item_authkey" >
<!-- e97cade544ef4ebc8dbd42c490b72b9e -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="17.0cm"/>
  <fo:table-body>
  <xsl:for-each select="ListOfReportDetailorder_item_authkey/ItemOfReportDetailorder_item_authkey">
    <fo:table-row   background-color="#ffffff"  height="1.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="4.0cm"/>
      <fo:table-column column-width="13.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="1.5cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <fo:block   margin-left="0.0cm"  margin-top="0.0cm" ><fo:instream-foreign-object  content-width="8.0cm" content-height="1.5cm"  >
<!-- a5796a55b286467bad0831c453894fdd -->
<j4lbarcode xmlns="http://java4less.com/j4lbarcode/fop" mode="inline"><Barcode1D><VALUE><xsl:value-of select="order_item_authkey"></xsl:value-of></VALUE><X>1</X><BARHEIGHT>30</BARHEIGHT><LEFTMARGIN>10</LEFTMARGIN><SET>A</SET><PROCESSTILDE>true</PROCESSTILDE><TYPE>CODE128</TYPE><N>2</N><TOPMARGIN>25</TOPMARGIN><CHECKSUM>true</CHECKSUM></Barcode1D></j4lbarcode></fo:instream-foreign-object></fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
  </xsl:for-each>
  </fo:table-body>
</fo:table>
</xsl:if>

<!-- END Area Detail 8 -->
</fo:block>
</fo:table-cell>
</fo:table-row>
  </xsl:for-each>
  </fo:table-body>
</fo:table>
</xsl:if>

<!-- END Area Detail 3 -->
</fo:block>
</fo:table-cell>
</fo:table-row>
  </xsl:for-each>
  </fo:table-body>
</fo:table>
</xsl:if>

<!-- END Area Detail -->
</fo:block>
</fo:table-cell>
</fo:table-row>
<fo:table-row>
<fo:table-cell number-columns-spanned="6">
<fo:block>

<!-- START Area Footer -->
<!-- c989248e86e643efa50a55ff8400d988 -->
<fo:table  width="17.0cm"   >
  <fo:table-column column-width="17.0cm"/>
  <fo:table-body>
    <fo:table-row   background-color="#ffffff"  height="0.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="17.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.5cm">
        <fo:table-cell    number-columns-spanned="1">
        <fo:block></fo:block>        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
    <fo:table-row   background-color="#ffffff"  height="0.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="4.0cm"/>
      <fo:table-column column-width="13.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.5cm">
        <fo:table-cell    number-columns-spanned="1">
      <!-- e3da5cbb985c46809e7c03fe2633a874 -->
      <fo:block     margin-left="0.0cm" margin-right="1.5cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Paketanzahl:</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 0b123818c0714cfcad4fca96fb1d4748 -->
      <fo:block     margin-left="0.0cm" margin-right="11.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="data/PackageDetail/ListOfPackageDetail/ItemOfPackageDetail/maxcount"></xsl:value-of></fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
    <fo:table-row   background-color="#ffffff"  height="0.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="4.0cm"/>
      <fo:table-column column-width="1.0cm"/>
      <fo:table-column column-width="12.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.5cm">
        <fo:table-cell    number-columns-spanned="1">
      <!-- 3f65ee75840545389b77da9a67bb9587 -->
      <fo:block     margin-left="0.0cm" margin-right="1.5cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Gesamtgewicht:</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 23de3513449f47528f0a3a0c0d4d343f -->
      <fo:block     margin-left="0.0cm" margin-right="0.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left"><xsl:value-of select="format-number(/data/PackageDetail/ListOfPackageDetail/ItemOfPackageDetail/weight, '#.00')"></xsl:value-of></fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- 23de3513449f47528f0a3a0c0d4d343f -->
      <fo:block     margin-left="0.0cm" margin-right="10.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">kg</fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
    <fo:table-row   background-color="#ffffff"  height="0.5cm">
      <fo:table-cell><fo:table>
      <fo:table-column column-width="4.0cm"/>
      <fo:table-column column-width="13.0cm"/>
      <fo:table-body>
      <fo:table-row   background-color="#ffffff"  height="0.5cm">
        <fo:table-cell    number-columns-spanned="1">
      <!-- d4157276208c4254b2e48e5df2b51859 -->
      <fo:block     margin-left="0.0cm" margin-right="2.65cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">Versand:</fo:block>
        </fo:table-cell>
        <fo:table-cell    number-columns-spanned="1">
      <!-- a4b786292ad045b0a4af73b02e0c6a18 -->
      <fo:block     margin-left="0.0cm" margin-right="9.0cm"  margin-top="0.0cm"  font-size="10pt"  font-family="HelveticaNeueLTStd-Md"  color="#000000"   text-align="left">per DHL</fo:block>
        </fo:table-cell>
    </fo:table-row>
    </fo:table-body>
    </fo:table></fo:table-cell>
    </fo:table-row>
  </fo:table-body>
</fo:table>

<!-- END Area Footer -->
</fo:block>
</fo:table-cell>
</fo:table-row>
  </fo:table-body>
</fo:table>

<!-- END Area Header -->
<fo:block id="last-page"/></fo:flow>
</fo:page-sequence>
</fo:root>
</xsl:template>
</xsl:stylesheet>