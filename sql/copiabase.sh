########################################################################
# Descrip: shellscript para realizar copias de bases de datos mysql
# Autor: Miguel Angel Borbon Lizcano
# Fecha: 30 de Mayo de 2024
# Para ser instalado en Hostinger servidor USAPOSTAL
#  
#######################################################################
#!/bin/bash
PATH=/usr/local/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/opt/golang/1.17.2/bin:/opt/go/bin
RUTA=/home/u704762597/domains/usapostal.com.co/public_html/sig/sql
export TZ="America/Bogota"
    #find $RUTA/*bz2 -mtime +30 -delete
    BASE='u704762597_sig'
    #Defino variable fecha formato YY/mm/dd-hh:mm para nombre de archivo
    FECHA=`date +%Y-%m-%d_%H:%M`
    mysqldump --user=u704762597_sig --password=Usapostal2021** u704762597_sig > $RUTA/sig_$FECHA.sql
    bzip2 $RUTA/sig_$FECHA.sql 
    echo "Copia DIARIA SIG; realizada el: $FECHA." >> $RUTA/Log_Copias_Mysql.txt

