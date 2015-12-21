/**
* GNU
**/
package com.flash.mp3player.player
{
	import com.flash.modules.text.Text;
	import com.flash.mp3player.player.components.*;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.text.*;
	import flash.utils.Timer;
	
	public class PlayerView extends Sprite
	{
		//constants
		private const border:uint = 3;
		private const infoMessage:			String = '<p align="center"><font face="Tahoma"' + 
				' size="11" color="#ffffff" letterspacing="0" kerning="0">MP3 Player&nbsp;v.1.0' + 
				'\n&nbsp;<a href="http://www.adobe.com" target="_blank"><u>Adobe.com</u></a></font></p>';
		
		//properties
		private var spPlayerBg:				PlayerBg;
		private var btnStop:				StopButton;
		private var btnPrev:				PrevSongButton;
		private var btnPlayPause:			PlayPauseButton;
		private var btnNext:				NextSongButton;	
		private var btnRepeat:				RepeatButton;
		private var btnMute:				MuteButton;
		private var btnShuffle:				ShuffleButton;
		private var btnCode:				CodeButton;
		private var btnInfo:				InfoBtn;
		private var spSplashScreen:			SplashScreen;
		
		private var spSongInfoBg:			SongInfoBg;
		private var spProgress:				ProgressSlider;		
		private var spVolume:				VolumeSlider;
		
		private var tfSongInfo:				Text;
		private var tfCurrentTime:			Text;
		
		private var timer:					Timer;		
		
		//constructor
		public function PlayerView()
		{
			super();
		}
		
		// setters
		public function set ShowBtnInfo(value:Boolean):void
		{
			if(this.btnInfo.visible == value) return;
			this.btnInfo.visible = value;
			var direction:int = value ? -1 : 1;
			Redraw( (this.btnInfo.width + 3) * direction);
		}

		public function set ShowBtnCode(value:Boolean):void
		{
			if(this.btnCode.visible == value) return;
			this.btnCode.visible = value;
			var direction:int = value ? -1 : 1;
			Redraw( (this.btnCode.width + 3) * direction);
		}
		
		//methods		
		public function Init(playerWidth:uint):void
		{
			this.addEventListener(Event.ADDED_TO_STAGE, this.AddedToStageHandler);
			
			this.spPlayerBg = new PlayerBg();
			this.spPlayerBg.Init(playerWidth);
			this.addChild(this.spPlayerBg);
			
			this.spSongInfoBg = new SongInfoBg();
			this.spSongInfoBg.x = this.spPlayerBg.x + 6;
			this.spSongInfoBg.y = this.spPlayerBg.y + 5;
			this.spSongInfoBg.Init(playerWidth - 2 * this.spSongInfoBg.x);
			this.addChild(this.spSongInfoBg);
			
			this.tfSongInfo = new Text("tfSongInfo", "");
			this.tfSongInfo.mouseEnabled = false;
			this.tfSongInfo.width = this.spSongInfoBg.width - 55;
			this.tfSongInfo.height = this.spSongInfoBg.height;
			this.tfSongInfo.x = this.spSongInfoBg.x + 11;
			this.tfSongInfo.y = this.spSongInfoBg.y + 2;
			this.addChild(this.tfSongInfo);
			
			this.tfCurrentTime = new Text("tfCurrentTime", "");
			this.tfCurrentTime.mouseEnabled = false;
			this.tfCurrentTime.height = this.spSongInfoBg.height;
			this.tfCurrentTime.y = this.spSongInfoBg.y + 2;
			this.addChild(this.tfCurrentTime);	
			
			this.spProgress = new ProgressSlider();
			this.spProgress.Init(this.spSongInfoBg.width);
			this.spProgress.x = this.spSongInfoBg.x;
			this.spProgress.y = this.spSongInfoBg.y + this.spSongInfoBg.height + 4;
			this.addChild(this.spProgress);
			
			this.btnPlayPause = new PlayPauseButton();
			this.btnPlayPause.Init();
			this.btnPlayPause.x = this.spSongInfoBg.x + 3;
			this.btnPlayPause.y = this.spProgress.y + this.spProgress.height + 2;
			this.addChild(this.btnPlayPause);
						
			this.btnStop = new StopButton();
			this.btnStop.Init();
			this.btnStop.x = this.btnPlayPause.x + this.btnPlayPause.width + 2;
			this.btnStop.y = this.btnPlayPause.y;
			this.addChild(this.btnStop);
			
			this.btnPrev = new PrevSongButton();
			this.btnPrev.Init();
			this.btnPrev.x = this.btnStop.x + this.btnStop.width + 2;
			this.btnPrev.y = this.btnPlayPause.y;
			this.addChild(this.btnPrev);			
			
			this.btnNext = new NextSongButton();
			this.btnNext.Init();
			this.btnNext.x = this.btnPrev.x + this.btnPrev.width + 2;
			this.btnNext.y = this.btnPlayPause.y;
			this.addChild(this.btnNext);
			
			
			this.btnInfo = new InfoBtn();
			this.btnInfo.Init();
			this.btnInfo.width = 0;
			this.btnInfo.x = this.spSongInfoBg.x + this.spSongInfoBg.width - this.btnInfo.width - 3;
			this.btnInfo.y = this.btnPlayPause.y + (this.btnPlayPause.height - this.btnInfo.height) / 2;
			this.btnInfo.addEventListener(MouseEvent.CLICK, this.InfoClickHandler);
			this.addChild(this.btnInfo);

			this.btnInfo.visible = false;
			
			this.btnCode = new CodeButton();
			this.btnCode.Init();
			this.btnCode.x = this.btnInfo.x - this.btnCode.width - 3;
			this.btnCode.y = this.btnPlayPause.y;
			this.btnCode.addEventListener(MouseEvent.CLICK, this.CodeClickHandler);
			this.addChild(this.btnCode);
			
			this.btnRepeat = new RepeatButton();
			this.btnRepeat.Init();
			this.btnRepeat.x = this.btnCode.x - this.btnRepeat.width - 3;
			this.btnRepeat.y = this.btnPlayPause.y;
			this.addChild(this.btnRepeat);
			RepeatOff();
			
			this.btnShuffle = new ShuffleButton();
			this.btnShuffle.Init();
			this.btnShuffle.x = this.btnRepeat.x - this.btnShuffle.width - 2;
			this.btnShuffle.y = this.btnPlayPause.y;
			this.addChild(this.btnShuffle);			
			
			this.spVolume = new VolumeSlider();
			this.spVolume.Init();
			this.spVolume.x = this.btnShuffle.x - this.spVolume.width - 6;
			this.spVolume.y = this.btnPlayPause.y + (this.btnPlayPause.height - this.spVolume.height) / 2 + 2;
			this.addChild(this.spVolume);
			
			this.btnMute = new MuteButton();
			this.btnMute.Init();
			this.btnMute.x = this.spVolume.x - this.btnMute.width - 3;
			this.btnMute.y = this.btnPlayPause.y + (this.btnPlayPause.height - this.btnMute.height) / 2;
			this.addChild(this.btnMute);
		}
		
		private function Redraw(delta:Number):void
		{
			/*
			this.btnInfo.x = this.spSongInfoBg.x + this.spSongInfoBg.width - this.btnInfo.width - 3;
			this.btnCode.x = this.btnInfo.x - this.btnCode.width - 3;
			this.btnRepeat.x = this.btnCode.x - this.btnRepeat.width - 3;
			this.btnShuffle.x = this.btnRepeat.x - this.btnShuffle.width - 2;
			this.spVolume.x = this.btnShuffle.x - this.spVolume.width - 6;
			this.btnMute.x = this.spVolume.x - this.btnMute.width - 3;
			*/
			this.btnCode.x += delta;
			this.btnRepeat.x += delta;
			this.btnShuffle.x += delta;
			this.spVolume.x += delta;
			this.btnMute.x += delta;
		}
		
		public function GetHeight():uint
		{
			return this.spPlayerBg.height;
		}
		
		public function ShowInfo(info:String):void
		{
			this.tfSongInfo.text = info;
			if(this.tfSongInfo.textWidth > this.tfSongInfo.width)
			{
				this.CreateCreepLine();
			}
			else
			{
				this.CreateCreepLine(false);
			}
		}
		
		public function ShowLoadingProgress(percentage:Number):void
		{
			this.spProgress.ShowLoadingProgress(percentage);
		}
		
		public function ShowPlayingProgress(percentage:Number):void
		{
			this.spProgress.ShowPlayingProgress(percentage);
		}
		
		public function ShowProgressBar(show:Boolean = true):void
		{
			this.spProgress.ShowProgressBar(show);
		}
		
		public function ShowCurrentTime(time:String = ""):void
		{
			this.tfCurrentTime.text = time;
			this.tfCurrentTime.width = this.tfCurrentTime.textWidth + 10;
			this.tfCurrentTime.x = this.spSongInfoBg.x + this.spSongInfoBg.width - 
				this.tfCurrentTime.width;
		}
		
		public function SetVolume(volume:Number):void
		{
			this.spVolume.SetVolume(volume);
		}
		
		public function Play():void
		{
			this.btnPlayPause.Play();
		}
		
		public function Pause():void
		{
			this.btnPlayPause.Pause();
		}
		
		public function Mute():void
		{
			this.btnMute.Mute();
		}
		
		public function Unmute():void
		{
			this.btnMute.Unmute();
		}
		
		public function RepeatOn():void
		{
			this.btnRepeat.TurnOn();
		}
		
		public function RepeatOff():void
		{
			this.btnRepeat.TurnOff();
		}
		
		public function HideSplashScreen():void
		{
			this.spSplashScreen.visible = false;
		}
		
		private function CreateCreepLine(create:Boolean = true):void
		{
			if(this.timer) this.timer.reset();
			
			if(create)
			{
				if(!this.timer)
				{
					this.timer = new Timer(2000, 1);
					this.timer.addEventListener(TimerEvent.TIMER, this.StartMoveCreepLine);
				}				
				this.timer.start();
			}
			else
			{
				this.tfSongInfo.scrollH = 0;
				this.removeEventListener(Event.ENTER_FRAME, this.MoveCreepLineHandler);
			}
		}
		
		private function StartMoveCreepLine(evt:TimerEvent):void
		{
			this.tfSongInfo.scrollH = 0;
			this.addEventListener(Event.ENTER_FRAME, this.MoveCreepLineHandler);
		}
		
		private function MoveCreepLineHandler(evt:Event):void
		{
			if(this.tfSongInfo.scrollH == this.tfSongInfo.maxScrollH)
			{
				this.removeEventListener(Event.ENTER_FRAME, this.MoveCreepLineHandler);
				
				this.timer.reset();
				this.timer.start();			
			}
			else
			{
				this.tfSongInfo.scrollH += 1;
			}
		}
		
		private function AddedToStageHandler(evt:Event):void
		{
			this.spSplashScreen = new SplashScreen();
			this.spSplashScreen.Init(this.stage);
			this.stage.addChild(this.spSplashScreen);
		}
		
		private function CodeClickHandler(evt:MouseEvent):void
		{
			this.btnCode.ShowCode(this.stage);
		}
		
		private function InfoClickHandler(evt:MouseEvent):void
		{
			this.btnCode.ShowInfo(this.stage, this.infoMessage);	
		}
	}
}