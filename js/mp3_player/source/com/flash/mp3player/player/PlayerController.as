/**
* GNU
**/
package com.flash.mp3player.player
{
	import com.flash.modules.utils.UDateTime;
	import com.flash.mp3player.common.CommonModel;
	import com.flash.mp3player.common.vo.SongVO;
	import com.flash.mp3player.player.events.*;
	
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.ProgressEvent;
	import flash.media.Sound;
	import flash.media.SoundChannel;
	import flash.media.SoundLoaderContext;
	import flash.media.SoundTransform;
	import flash.net.URLRequest;
	
	public class PlayerController
	{		
		//properties
		private var playerModel:		PlayerModel;
		private var playerView:			PlayerView;
		
		private var sound:				Sound;
		private var soundLoaderContext:	SoundLoaderContext;
		private var arSounds:			Array; // for each SongVO new Sound object created and is kept here
		private var soundChannel:		SoundChannel;
		
		//constructor
		public function PlayerController() {}
		
		//methods
		public function Init(playerModel:PlayerModel, playerView:PlayerView):void
		{
			this.playerModel = playerModel;
			
			this.arSounds = new Array();
			this.soundLoaderContext = new SoundLoaderContext(1000, true);
			
			this.playerView = playerView;
			this.playerView.addEventListener(PlayPauseClickEvent.PLAY_PAUSE_CLICK, this.PlayPauseHandler);
			this.playerView.addEventListener(StopClickEvent.STOP, this.StopHandler);
			this.playerView.addEventListener(VolumeEvent.CHANGE_VOLUME, this.VolumeHandler);
			this.playerView.addEventListener(MuteClickEvent.MUTE, this.MuteHandler);
			this.playerView.addEventListener(RepeatClickEvent.REPEAT, this.RepeatHandler);
			this.playerView.addEventListener(SeekEvent.SEEK, this.SeekHandler);
			this.playerView.addEventListener(ShuffleClickEvent.SHUFFLE, this.ShuffleHandler);
			this.playerView.addEventListener(NextClickEvent.NEXT, this.NextHandler);
			this.playerView.addEventListener(PrevClickEvent.PREV, this.PrevHandler);
		}
		
		public function ApplySettings(xml:XML):void
		{
			var showBtnHtml:	Boolean = true;
			var showBtnInfo:	Boolean = true;
			
			if(String(xml.settings.showBtnHtml).length != 0
				&& String(xml.settings.showBtnHtml) != "true")
			{
				showBtnHtml = false;
			}
			if(String(xml.settings.showBtnInfo).length != 0
				&& String(xml.settings.showBtnInfo) != "true")
			{
				showBtnInfo = false;
			}
			this.playerView.ShowBtnCode = showBtnHtml;
			this.playerView.ShowBtnInfo = showBtnInfo;
		}
		
		public function ShowFirstSong(songVO:SongVO):void
		{
			this.playerModel.curSong = songVO;
			
			this.playerView.ShowInfo(songVO.uid + ". " + songVO.artist + " - " + songVO.title);
			this.playerView.ShowCurrentTime("00:00");
		}
		
		public function PlaySong(songVO:SongVO):void
		{
			if(songVO == null) return;
			
			if(this.sound)
			{
				try
				{
					this.sound.close();
					this.sound.removeEventListener(Event.ID3, this.ID3Handler);
				}
				catch(e:Error)
				{
					//throw new Error(e.message);
				}
			}
			this.sound = new Sound();
			this.sound.addEventListener(ProgressEvent.PROGRESS, this.LoadingProgressHandler);
			this.sound.addEventListener(IOErrorEvent.IO_ERROR, this.IOErrorHandler);
			this.sound.addEventListener(Event.ID3, this.ID3Handler);
			this.sound.load(new URLRequest(songVO.url), this.soundLoaderContext);
			
			this.arSounds[songVO.uid] = this.sound;
			
			if(this.soundChannel)
			{
				this.soundChannel.stop();
				this.soundChannel.removeEventListener(Event.SOUND_COMPLETE, this.SongCompleteHandler);
			}
			this.soundChannel = this.sound.play();
			this.soundChannel.addEventListener(Event.SOUND_COMPLETE, this.SongCompleteHandler);
			
			this.playerModel.curSong = songVO;
			this.playerModel.isPlaying = true;
			
			if(songVO.length !=0)
			{
				this.playerView.ShowProgressBar();
			}
			else
			{
				this.playerView.ShowProgressBar(false);
			}
			this.playerView.ShowInfo(songVO.uid + ". " + songVO.artist + " - " + songVO.title);
			this.playerView.Play();	
			this.playerView.ShowLoadingProgress(0);
			this.playerView.addEventListener(Event.ENTER_FRAME, this.PlayingProgressHandler);
			
			UpdateVolume();		
		} 
		
		private function ID3Handler(evt:Event):void
		{
			var sound:Sound = evt.target as Sound;
			
			var songVO:SongVO = this.playerModel.curSong;
			if(sound.id3.TPE1 != "") songVO.artist = sound.id3.TPE1;
			if(sound.id3.TIT2 != "") songVO.title = sound.id3.TIT2;
			
			this.playerModel.curSong = songVO;
			
			this.playerView.ShowInfo(songVO.uid + ". " + songVO.artist + " - " + songVO.title);
			this.playerView.dispatchEvent(new UpdateEvent(this.playerModel.curSong));
		}
		
		private function PlayingProgressHandler(evt:Event):void
		{
			var curPosition:Number;
			if(this.playerModel.isPlaying)
			{
				curPosition = this.soundChannel.position;
			}
			else
			{
				curPosition = this.playerModel.curPosition;
			}
			this.playerView.ShowPlayingProgress(curPosition /
				this.playerModel.curSong.length / 1000);
			this.playerView.ShowCurrentTime(UDateTime.ConvertToMinutesStr(curPosition / 1000));
		}
		
		private function LoadingProgressHandler(evt:ProgressEvent):void
		{
			this.playerView.ShowLoadingProgress(evt.bytesLoaded / evt.bytesTotal);
		}
		
		private function IOErrorHandler(evt:IOErrorEvent):void
		{
			throw new Error(evt.text);
		}
		
		private function SongCompleteHandler(evt:Event):void
		{
			this.playerModel.curPosition = 0;
			this.playerModel.isPlaying = false;
				
			this.playerView.ShowPlayingProgress(0);
			this.playerView.Pause();
			this.playerView.removeEventListener(Event.ENTER_FRAME, this.PlayingProgressHandler);
			
			this.NextHandler(new NextClickEvent());
		}
		
		private function PlayPauseHandler(evt:PlayPauseClickEvent):void
		{
			if(this.soundChannel)
			{
				if(this.playerModel.isPlaying)
				{
					this.playerModel.curPosition = this.soundChannel.position;
					this.soundChannel.stop();
					this.playerView.Pause();
				}
				else
				{
					var sound:Sound = this.arSounds[this.playerModel.curSong.uid];
					
					this.soundChannel.removeEventListener(Event.SOUND_COMPLETE, this.SongCompleteHandler);
					this.soundChannel = sound.play(this.playerModel.curPosition);
					this.soundChannel.addEventListener(Event.SOUND_COMPLETE, this.SongCompleteHandler);
					
					this.playerView.Play();		
					this.playerView.addEventListener(Event.ENTER_FRAME, this.PlayingProgressHandler);
					
					UpdateVolume();			
				}
				this.playerModel.isPlaying = !this.playerModel.isPlaying;
			}
			else
			{
				this.PlaySong(this.playerModel.curSong);
				
				this.playerView.dispatchEvent(new FirstSongStartEvent());
			}
		}
		
		private function StopHandler(evt:StopClickEvent):void
		{
			if(this.soundChannel)
			{
				this.soundChannel.stop();
				
				this.playerModel.curPosition = 0;
				this.playerModel.isPlaying = false;
				
				this.playerView.removeEventListener(Event.ENTER_FRAME, this.PlayingProgressHandler);
				this.playerView.Pause();				
				this.playerView.ShowPlayingProgress(0);
				this.playerView.ShowCurrentTime();			
			}			
		}
		
		private function SeekHandler(evt:SeekEvent):void
		{
			if(this.soundChannel)
			{
				if(this.playerModel.isPlaying)
				{
					this.soundChannel.stop();
					var sound:Sound = this.arSounds[this.playerModel.curSong.uid];
					this.soundChannel.removeEventListener(Event.SOUND_COMPLETE, this.SongCompleteHandler);
					this.soundChannel = sound.play((evt.seek) *	this.playerModel.curSong.length * 1000);
					this.soundChannel.addEventListener(Event.SOUND_COMPLETE, this.SongCompleteHandler);
					
					UpdateVolume();
				}
				else
				{
					this.playerModel.curPosition = evt.seek * this.playerModel.curSong.length * 1000;
				}								
			}
		}
		
		private function ShuffleHandler(evt:ShuffleClickEvent):void
		{
			CommonModel.GetInst().shuffle = !CommonModel.GetInst().shuffle;
		}
		
		private function VolumeHandler(evt:VolumeEvent):void
		{
			this.playerModel.curVolume = evt.volume;
			if ( this.playerModel.curVolume )
				this.playerModel.muted = false;
			UpdateVolume();
		}
		
		private function UpdateVolume():void
		{
			if( this.playerModel.curVolume == 0 || this.playerModel.muted )
			{
				this.playerView.Mute();
				this.playerModel.muted = true;
			}
			else
			{
				this.playerView.Unmute();
				this.playerModel.muted = false;
			}
			
			if ( this.soundChannel )
				this.soundChannel.soundTransform = new SoundTransform( 
					this.playerModel.muted ? 0 : this.playerModel.curVolume );
		}
		
		private function MuteHandler(evt:MuteClickEvent):void
		{
			this.playerModel.muted = evt.mute;
			UpdateVolume();
		}
		
		private function RepeatHandler(evt:RepeatClickEvent):void
		{
			if(evt.repeat) this.playerView.RepeatOn();
			else this.playerView.RepeatOff();

			this.playerModel.isRepeated = evt.repeat;
			CommonModel.GetInst().repeate = evt.repeat;
		}
		
		private function NextHandler(evt:NextClickEvent):void
		{
			if(this.soundChannel)
			{
				this.playerView.dispatchEvent(new NextPrevEvent(NextPrevEvent.NEXT));
			}			
		}
		
		private function PrevHandler(evt:PrevClickEvent):void
		{
			if(this.soundChannel)
			{
				this.playerView.dispatchEvent(new NextPrevEvent(NextPrevEvent.PREV));
			}			
		}
	}
}