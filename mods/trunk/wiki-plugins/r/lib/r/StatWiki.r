outHTML <- function (html, x, title='', 
                     style ='width:100%;', 
                     tstyle='background-color:#BBBBBB; vertical-align:top; text-align:left;', 
                     cstyle='background-color:#BBBBBB; vertical-align:top; text-align:right;', 
                     rstyle='background-color:#BBBBBB; vertical-align:top; text-align:right;', 
                     ostyle='background-color:#FFFFFF; vertical-align:top; text-align:left;',
                     estyle='background-color:#CCCCCC; vertical-align:top; text-align:left;', 
                     header=c(0), 
                     ...) {
# beginne tabelle
  cat (paste('<table style="', style, '">\n', sep=""), file=html, append=TRUE)
# header line
  cat (paste('<tr><td style="', tstyle, '">', title, '</td>', sep=""), file=html, append=TRUE)
  dimx  <- dim(x)
  names <- colnames(x)
  for (i in 1:dimx[2]) {
    cat (paste('<td style="', cstyle, '">', names[i], '</td>', sep=""), file=html, append=TRUE)
  }
  cat ('</tr>\n', file = html, append=TRUE)
# schreibe jede zeile
  names <- rownames(x)
  rows  <- paste ('<tr><td style="', rstyle, '">', names, '</td>', sep="")
  for (i in 1:dimx[2]) {
    rows <- paste (rows, '<td style="', ifelse((1:dimx[1])%%2, ostyle, estyle), '">', formatC(x[,i], ...), '</td>', sep="")
  }
  rows <- paste (rows, '</tr>\n', sep="")
# repeat colnames
  dimx  <- dim(x)
  cnames <- colnames(x)
  for (i in 1:length(header)) {
    j = header[i]
    if ((j>0) && (j<=length(names))) {
      row <- paste('<tr><td style="', tstyle, '">', names[j], '</td>', sep="")
      for (i in 1:dimx[2]) {
        row <- paste(row, '<td style="', cstyle, '">', cnames[i], '</td>', sep="")
      }
      rows[j] <- paste (row, '</tr>\n', sep="")
    }
  }
  for (i in 1:dimx[1]) {
    cat (rows[i], file=html, append=TRUE)
  }
# end der tabelle
  cat ('</table>\n', file=html, append=TRUE)
}

trellisSK <- function (file, ...) {
  trellis.device ("pdf", file=file, ...)
} 

readdataSK <- function (name, format = "csv", ...) {
  fullname <- list.files(pattern=name, recursive=TRUE, full.names=TRUE)
# if filename not unique then return nothing !!
  if (length(fullname)>1) {
    stop("More than one file found", fullname)
  }
  switch (format,
    csv   = read.csv (file=fullname, ...),
    csv2  = read.csv2 (file=fullname, ...),
    table = read.table (file=fullname, ...),
    txt   = read.table (textConnection(name), ...),
    default = NULL)
}