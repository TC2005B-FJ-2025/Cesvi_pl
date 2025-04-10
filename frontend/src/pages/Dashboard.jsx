import React, { useEffect } from 'react'

//MIU
import Grid from "@mui/material/Grid";
import Box from "@mui/material/Box";

import Card from '@mui/joy/Card';
import CardCover from '@mui/joy/CardCover';
import CardContent from '@mui/joy/CardContent';
import Typography from '@mui/joy/Typography';


const Dashboard = (props) => {

  // useEffect(() => {
  //   console.log("prueba")
  // }, [])


  return (
    <>
      <Box
        sx={{
          display: "flex",
          flexWrap: "wrap",
          "& > :not(style)": { m: 1, width: "99%", height: "100%" },
        }}
      >
        <Grid container spacing={1}>
          <Card 
          component="li" 
          sx={{ minWidth: "100%", flexGrow: 1  ,  height: "100%" }}
         
          >
            <CardCover>
              <video
                autoPlay
                loop
                muted
                poster="https://assets.codepen.io/6093409/river.jpg"
              >
                <source
                  src="https://video.wixstatic.com/video/7822f6_e1fa3a37664a47678e1bde1fa1283209/720p/mp4/file.mp4"
                  type="video/mp4"
                />
              </video>
            </CardCover>
            <CardContent>
              <Typography
                level="body-lg"
                fontWeight="lg"
                textColor="#fff"
                mt={{ xs: 12, sm: 18 }}
                style={{ width: "80%", height: "260px" }}
              >                
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Box>
    </>
  );
};

export default Dashboard;
